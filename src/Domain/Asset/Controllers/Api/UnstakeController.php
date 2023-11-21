<?php

namespace Domain\Asset\Controllers\Api;

use Domain\Asset\Entities\Asset;
use Domain\Player\Models\Player;
use Illuminate\Http\JsonResponse;
use Domain\Asset\Entities\Service;
use App\Jobs\UnstakeTransactionJob;
use App\Http\Controllers\Controller;
use Domain\Asset\Models\StakedAsset;
use Domain\Log\Enums\TransactionType;
use Domain\Shared\Enums\MessageStatus;
use Domain\Log\Enums\TransactionStatus;
use Domain\Asset\Requests\UnstakeRequest;
use Domain\Asset\Effects\MajorPlazaEffect;
use Domain\Player\ValueObjects\PlayerServices;
use Domain\Asset\Collections\StakedAssetCollection;
use Domain\Asset\Actions\GetServiceConsumptionAction;

class UnstakeController extends Controller
{
    public function __invoke(UnstakeRequest $request): JsonResponse
    {
        /** @var Player */
        $player = auth()->user();

        if ($player->has_processing_transaction) {
            return $this->alreadyProcessingTransactionResponse();
        }

        if (StakedAsset::where('asset_id', $request->input('asset_id'))->doesntExist()) {
            return $this->assetDoesntExistResponse();
        }

        $stakedAssets = $player->stakedAssets;

        /** @var Asset */
        $asset = $stakedAssets
            ->where('asset_id', $request->input('asset_id'))
            ->first()
            ->asset();

        if ($asset->schema === Service::SCHEMA_NAME) {
            if (! $this->hasEnoughServiceCapacity($player->stakedAssets, $asset)) {
                return $this->cantRemoveByConsumptionLimit();
            }

            /** @var StakedAsset */
            $stakedAssets->where('asset_id', $asset->assetId)->first()->delete();

            return response()
                ->json([
                    'success' => true,
                    'flash' => [
                        'status' => MessageStatus::Information,
                        'message' => __('messages.transaction.processing'),
                    ],
                ]);
        }

        $effect = $asset->effect();

        if ($effect instanceof MajorPlazaEffect) {
            if (! $effect->canBeUnstaked($stakedAssets)) {
                return $this->cantUnstakeMajorPlazaResponse();
            }
        }

        $transactionLog = $player
            ->transactionLogs()
            ->create([
                'wallet_id' => $player->account_id,
                'asset_ids' => [$asset->assetId],
                'type' => TransactionType::Unstake,
                'status' => TransactionStatus::Processing,
            ]);

        dispatch(
            new UnstakeTransactionJob($player->id, $transactionLog->id, $asset->assetId)
        );

        return response()
            ->json([
                'success' => true,
                'flash' => [
                    'status' => MessageStatus::Information,
                    'message' => __('messages.transaction.processing'),
                ],
            ]);
    }

    protected function hasEnoughServiceCapacity(StakedAssetCollection $stakedAssets, Asset $asset): bool
    {
        if ($asset->schema !== Service::SCHEMA_NAME) {
            return true;
        }

        /** @var PlayerServices */
        $consumption = GetServiceConsumptionAction::execute($stakedAssets);

        if ($asset->type === Service::WATER_TYPE) {
            if ($consumption->water->current === 0) {
                return true;
            }

            if ($consumption->water->total - $asset->capacity < 0) {
                return false;
            }
        }

        if ($asset->type === Service::ENERGY_TYPE) {
            if ($consumption->energy->current === 0) {
                return true;
            }

            if ($consumption->energy->total - $asset->capacity < 0) {
                return false;
            }
        }

        return false;
    }

    /**
     * @return JsonResponse
     */
    protected function assetDoesntExistResponse(): JsonResponse
    {
        return response()->json([
            'success' => false,
            'flash' => [
                'message' => __('messages.errors.asset_doesnt_exist'),
                'status' => MessageStatus::Danger,
            ],
        ], 422);
    }

    /**
     * @return JsonResponse
     */
    protected function cantRemoveByConsumptionLimit(): JsonResponse
    {
        return response()->json([
            'success' => false,
            'flash' => [
                'message' => __('messages.unstake.cant_be_under_consumption_limit'),
                'status' => MessageStatus::Danger,
            ],
        ], 422);
    }

    /**
     * @return JsonResponse
     */
    protected function cantUnstakeMajorPlazaResponse(): JsonResponse
    {
        return response()
            ->json([
                'success' => false,
                'flash' => [
                    'message' => __('messages.unstake.limit.major_plaza'),
                    'status' => MessageStatus::Danger,
                ],
            ], 422);
    }

    /**
     * @return JsonResponse
     */
    protected function alreadyProcessingTransactionResponse(): JsonResponse
    {
        return response()
            ->json([
                'success' => false,
                'flash' => [
                    'message' => __('messages.transaction.already_processing'),
                    'status' => MessageStatus::Danger,
                ],
            ], 422);
    }
}
