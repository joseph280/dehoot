<?php

namespace Domain\EosPhp\Entities\Transaction;

class TransactionTrace
{
    public function __construct(
        public string $id,
        public int $block_num,
        public string $block_time,
        public ?string $producer_block_id,
        public ?TransactionReceiptHeader $receipt,
        public int $elapsed,
        public int $net_usage,
        public bool $scheduled,
        public array $action_traces,
        public ?array $account_ram_delta,
        public ?string $except,
        public ?int $error_code,
        public array $bill_to_accounts,
    ) {
    }
}
