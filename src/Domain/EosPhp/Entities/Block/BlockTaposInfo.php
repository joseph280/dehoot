<?php

namespace Domain\EosPhp\Entities\Block;

class BlockTaposInfo
{
    public function __construct(
        public int $block_num,
        public string $id,
        public ?string $timestamp,
        public ?BlockHeader $header,
    ) {
    }
}
