<?php

namespace Domain\EosPhp\Entities\Transaction;

class TransactionReceipt
{
    public function __construct(
        public ?string $transaction_id = '',
        public TransactionTrace | array | null $processed = [],
    ) {
    }
}
