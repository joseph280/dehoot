<?php

namespace Domain\EosPhp\Entities\Block;

class BlockHeader
{
    public function __construct(
        public string $id,
        public int $block_num,
        public Block $header,
        public string $dpos_proposed_irreversible_blocknum,
        public string $dpos_irreversible_blocknum,
        public $pending_schedule,
        public $active_schedule,
        public $blockroot_merkle,
        public array $producer_to_last_produced,
        public $producer_to_last_implied_irb,
        public array $confirm_count,
        public ?array $confirmations = null,
        public ?string $block_signing_key = null,
        public ?string $pending_schedule_hash = null,
        public ?string $bft_irreversible_blocknum = null,
        public ?string $pending_schedule_lib_num = null,
        public ?array $valid_block_signing_authority = null,
        public ?array $activated_protocol_features = null,
        public ?array $additional_signatures = null,
    ) {
    }
}
