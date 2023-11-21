<?php

namespace Domain\EosPhp\Entities\Code;

class BinaryAbi
{
    public function __construct(
        public string $accountName,
        public string $abi,
    ) {
    }
}
