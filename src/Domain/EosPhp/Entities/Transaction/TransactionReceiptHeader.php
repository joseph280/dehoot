<?php

namespace Domain\EosPhp\Entities\Transaction;

class TransactionReceiptHeader
{
    public function __construct(
        public string $status,
        public int $cpu_usage_us,
        public int $net_usage_words
    ) {
    }
}
