<?php

namespace Domain\EosPhp\Entities\Account;

class ResourcePayer
{
    public function __construct(
        protected string $payer,
        protected int $max_net_bytes,
        protected int $max_cpu_us,
        protected int $max_memory_bytes
    ) {
    }
}
