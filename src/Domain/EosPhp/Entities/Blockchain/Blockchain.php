<?php

namespace Domain\EosPhp\Entities\Blockchain;

class Blockchain
{
    public function __construct(
        public string $server_version,
        public string $chain_id,
        public int $head_block_num,
        public int $last_irreversible_block_num,
        public string $last_irreversible_block_id,
        public string $head_block_id,
        public string $head_block_time,
        public string $head_block_producer,
        public int $virtual_block_cpu_limit,
        public int $virtual_block_net_limit,
        public int $block_cpu_limit,
        public int $block_net_limit,
        public mixed $total_cpu_weight = null,
        public ?string $last_irreversible_block_time = null,
        public ?string $server_version_string = null,
        public ?int $fork_db_head_block_num = null,
        public ?string $fork_db_head_block_id = null,
        public ?string $server_full_version_string = null,
        public ?int $first_block_num = null,
    ) {
    }
}
