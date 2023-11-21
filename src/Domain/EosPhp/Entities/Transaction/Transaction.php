<?php

namespace Domain\EosPhp\Entities\Transaction;

class Transaction
{
    public function __construct(
        public int | string | null $expiration = 0,
        public ?int $ref_block_num = 0,
        public ?int $ref_block_prefix = 0,
        public ?int $max_net_usage_words = 0,
        public ?int $max_cpu_usage_ms = 0,
        public ?int $delay_sec = 0,
        public ?array $context_free_actions = [],
        public $actions = [],
        public ?array $transaction_extensions = [],
    ) {
    }
}
