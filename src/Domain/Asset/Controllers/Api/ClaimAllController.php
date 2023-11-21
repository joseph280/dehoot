<?php

namespace Domain\Asset\Controllers\Api;

use Domain\Player\Models\Player;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Domain\Log\Enums\TransactionType;
use Domain\Shared\Enums\MessageStatus;
use Domain\Log\Enums\TransactionStatus;
use Domain\Asset\Jobs\ClaimAllTransactionJob;

class ClaimAllController extends Controller
{
    public function __invoke(): JsonResponse
    {
        /** @var Player */
        $player = auth()->user();

        if ($player->has_processing_transaction) {
            return $this->alreadyProcessingTransactionResponse();
        }

        $transactionLog = $player
            ->transactionLogs()
            ->create([
                'wallet_id' => $player->account_id,
                'asset_ids' => $player->stakedAssets->pluck('asset_id'),
                'type' => TransactionType::ClaimAll,
                'status' => TransactionStatus::Processing,
            ]);

        dispatch(new ClaimAllTransactionJob($player->id, $transactionLog->id));

        return response()
            ->json([
                'success' => true,
                'flash' => [
                    'status' => MessageStatus::Information,
                    'message' => __('messages.transaction.processing'),
                ],
            ]);
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
                    'status' => MessageStatus::Danger,
                    'message' => __('messages.transaction.already_processing'),
                ],
            ], 422);
    }
}
