<?php

namespace Domain\EosPhp\Entities\Code;

class CachedAbi
{
    public function __construct(
        public ?string $account_name = null,
        public ?string $code_hash = null,
        public ?string $abi_hash = null,
        public ?string $abi = null,
        public ?string $decoded_abi = null
    ) {
    }
}
