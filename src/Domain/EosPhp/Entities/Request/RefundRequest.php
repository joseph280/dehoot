<?php

namespace Domain\EosPhp\Entities\Request;

class RefundRequest
{
    public function __construct(
        public string $owner,
        public string $request_time,
        public string $net_amount,
        public string $cpu_amount,
    ) {
    }
}
