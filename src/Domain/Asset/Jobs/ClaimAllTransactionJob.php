<?php

namespace Domain\Asset\Jobs;

use Throwable;
use Illuminate\Bus\Queueable;
use Domain\Player\Models\Player;
use Domain\Log\Models\TransactionLog;
use Illuminate\Queue\SerializesModels;
use Domain\Log\Enums\TransactionStatus;
use Domain\Asset\Actions\ClaimAllAction;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\Middleware\WithoutOverlapping;

class ClaimAllTransactionJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    protected string $playerId;

    protected string $transactionId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $playerId, string $transactionId)
    {
        $this->playerId = $playerId;
        $this->transactionId = $transactionId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        /** @var Player */
        $player = Player::findOrFail($this->playerId);

        $stakedAssets = $player->stakedAssets->whereStakedAtOlderThanOneDay();

        ClaimAllAction::execute($stakedAssets, $player);
    }

    /**
     * Handle a job failure.
     *
     * @param  \Throwable  $exception
     *
     * @return void
     */
    public function failed(Throwable $exception)
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
        return [(new WithoutOverlapping($this->playerId))->expireAfter(180)];
    }
}
