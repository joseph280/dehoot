<?php

namespace Domain\EosPhp\Entities\Action;

class TransactAction
{
    public function __construct(
        public string $account,
        public string $name,
        public ?array $authorization = [],
        public array | string | null $data = [],
    ) {
    }
}
