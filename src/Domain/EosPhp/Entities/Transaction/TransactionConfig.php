<?php

namespace Domain\EosPhp\Entities\Transaction;

class TransactionConfig
{
    public function __construct(
        public bool $broadcast = true,
        public bool $sign = true,
        public ?bool $readOnlyTrx = null,
        public ?bool $returnFailureTraces = null,
        public ?bool $requiredKeys = null,
        public ?bool $compression = null,
        public ?int $blocksBehind = null,
        public ?bool $useLastIrreversible = null,
        public ?int $expireSeconds = null
    ) {
    }
}
