<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Domain\Log\Models\TransactionLog;
use Domain\Log\Enums\TransactionStatus;

class DeleteTransactionLogsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transaction-logs:delete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete all transaction logs that are older than a month';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        TransactionLog::whereDate('created_at', '<=', now()->subMonth())
            ->where('status', '!=', TransactionStatus::Failed)
            ->delete();
    }
}
