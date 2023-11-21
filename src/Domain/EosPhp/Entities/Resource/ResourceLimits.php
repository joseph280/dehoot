<?php

namespace Domain\EosPhp\Entities\Resource;

class ResourceLimits
{
    public function __construct(
        public string | int $max,
        public string | int $available,
        public string | int $used,
    ) {
    }
}
