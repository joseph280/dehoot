<?php

namespace Domain\EosPhp\Entities\Block;

class Block
{
    public function __construct(
        public string $timestamp,
        public string $producer,
        public int $confirmed,
        public string $previous,
        public string $transaction_mroot,
        public string $action_mroot,
        public int $schedule_version,
        public $new_producers = null,
        public ?array $header_extensions = null,
        public ?array $new_protocol_features = null,
        public ?string $producer_signature = null,
        public $transactions = null,
        public ?array $block_extensions = null,
        public ?string $id = null,
        public ?int $block_num = null,
        public ?int $ref_block_num = null,
        public ?int $ref_block_prefix = null,
    ) {
    }
}
