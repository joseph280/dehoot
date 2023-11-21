<?php

namespace Domain\EosPhp\Entities\Resource;

class ResourceOverview
{
    public function __construct(
        public string $owner,
        public string | int $ram_bytes,
        public string $net_weight,
        public string $cpu_weight
    ) {
    }
}
