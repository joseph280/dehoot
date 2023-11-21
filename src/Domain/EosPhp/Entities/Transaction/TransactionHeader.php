<?php

namespace Domain\EosPhp\Entities\Transaction;

class TransactionHeader
{
    public function __construct(
        public string | int | null $expiration,
        public int $ref_block_num,
        public int $ref_block_prefix,
    ) {
    }
}
