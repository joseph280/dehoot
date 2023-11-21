<?php

namespace Domain\EosPhp\Entities\Resource;

class ResourceDelegation
{
    public function __construct(
        public string $from,
        public string $to,
        public string $net_weight,
        public string $cpu_weight,
    ) {
    }
}
