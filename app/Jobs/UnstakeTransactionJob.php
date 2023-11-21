<?php

namespace App\Jobs;

use Throwable;
use Illuminate\Bus\Queueable;
use Domain\Player\Models\Player;
use Domain\Log\Models\TransactionLog;
use Illuminate\Queue\SerializesModels;
use Domain\Asset\Actions\UnstakeAction;
use Domain\Log\Enums\TransactionStatus;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\Middleware\WithoutOverlapping;

class UnstakeTransactionJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public string $playerId;

    public string $transactionId;

    public string $assetId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $playerId, string $transactionId, string $assetId)
    {
        $this->playerId = $playerId;
        $this->transactionId = $transactionId;
        $this->assetId = $assetId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        /** @var Player */
        $player = Player::findOrFail($this->playerId);

        $stakedAsset = $player->stakedAssets
            ->where('asset_id', $this->assetId)
            ->first();

        UnstakeAction::execute($stakedAsset, $player);
    }

    /**
     * Handle a job failure.
     *
     * @param Throwable $exception
     *
     * @return void
     */
    public function failed(Throwable $exception): void
    {
        TransactionLog::where('id', $this->transactionId)
            ->where('status', TransactionStatus::Processing)
            ->update([
                'status' => TransactionStatus::Failed,
            ]);
    }

    /**
     * Get the middleware the job should pass through.
     *
     * @return array
     */
    public function middleware()
    {
        return [(new WithoutOverlapping($this->playerId . $this->transactionId))->expireAfter(180)];
    }
}
