<?php

namespace Domain\Asset\Controllers\Api;

use Exception;
use Domain\Asset\Entities\Asset;
use Domain\Player\Models\Player;
use App\Http\Controllers\Controller;
use Domain\Asset\Models\StakedAsset;
use Domain\Asset\Entities\Residential;
use Domain\Shared\Enums\MessageStatus;
use Domain\Asset\Requests\StakeRequest;
use Domain\Asset\ValueObjects\Position;
use Domain\Asset\Collections\AssetCollection;
use Domain\Asset\Repositories\AssetsRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Domain\Asset\Collections\StakedAssetCollection;
use Domain\Asset\Actions\CheckServiceConsumptionAction;

class StakeController extends Controller
{
    /**
     * @throws Exception
     */
    public function __invoke(StakeRequest $request, AssetsRepository $assetRepository): JsonResponse
    {
        /** @var Player */
        $player = auth()->user();

        /** @var AssetCollection */
        $assets = $assetRepository->getFreshPlayerAssets($player);

        /** @var StakedAsset | null */
        $stakedAsset = StakedAsset::where('asset_id', $request->input('asset_id'))->first();

        if ($stakedAsset && $stakedAsset->player_id === $player->id) {
            return $this->assetAlreadyInStakingResponse();
        }

        if ($stakedAsset && $stakedAsset->player_id !== $player->id) {
            $stakedAsset->delete();
        }

        if ($player->stakedAssets->haveReachedStakedLimit()) {
            return $this->reachedStakingLimitResponse();
        }

        /** @var Asset */
        $asset = $assets->firstWhereAssetId($request->input('asset_id'));

        if ($asset->schema === Residential::SCHEMA_NAME) {
            if (! $this->hasEnoughServiceCapacity($player->stakedAssets, $asset)) {
                return $this->notEnoughServiceCapacityResponse();
            }
        }

        $position = Position::from(
            $request->input('position_x'),
            $request->input('position_y')
        );

        $stakedAsset = StakedAsset::create([
            'asset_id' => $asset->assetId,
            'land' => $request->input('land'),
            'position_x' => $position->x,
            'position_y' => $position->y,
            'data' => $asset,
            'player_id' => auth()->id(),
            'staked_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'data' => $stakedAsset,
        ], 201);
    }

    protected function assetAlreadyInStakingResponse(): JsonResponse
    {
        return response()->json([
            'success' => false,
            'flash' => [
                'message' => __('messages.errors.asset_already_in_staking'),
                'status' => MessageStatus::Danger,
            ],
        ], 422);
    }

    protected function reachedStakingLimitResponse(): JsonResponse
    {
        return response()->json([
            'success' => false,
            'flash' => [
                'message' => __('messages.stake.reached_stake_limit'),
                'status' => MessageStatus::Danger,
            ],
        ], 422);
    }

    protected function hasEnoughServiceCapacity(StakedAssetCollection $stakedAssets, Asset $asset): bool
    {
        /** @var bool */
        return CheckServiceConsumptionAction::execute($stakedAssets, $asset);
    }

    protected function notEnoughServiceCapacityResponse(): JsonResponse
    {
        return response()->json([
            'success' => false,
            'flash' => [
                'message' => __('messages.stake.reached_consumption_limit'),
                'status' => MessageStatus::Danger,
            ],
        ], 422);
    }
}
