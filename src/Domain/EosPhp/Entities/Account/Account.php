<?php

namespace Domain\EosPhp\Entities\Account;

use Domain\EosPhp\Entities\Request\RefundRequest;
use Domain\EosPhp\Entities\Resource\ResourceLimits;
use Domain\EosPhp\Entities\Resource\ResourceOverview;
use Domain\EosPhp\Entities\Resource\ResourceDelegation;

class Account
{
    public function __construct(
        public string $account_name,
        public int $head_block_num,
        public string $head_block_time,
        public bool $privileged,
        public string $last_code_update,
        public string $created,
        public ?string $core_liquid_balance = null,
        public string | int $ram_quota,
        public string | int $net_weight,
        public string | int $cpu_weight,
        public ResourceLimits $net_limit,
        public ResourceLimits $cpu_limit,
        public string | int $ram_usage,
        public array $permissions,
        public array | null | ResourceOverview $total_resources = [],
        public array | null | ResourceDelegation $self_delegated_bandwidth = [],
        public array | null | RefundRequest $refund_request = [],
        public array | ResourceLimits | null $subjective_cpu_bill_limit = [],
        public ?array $voter_info = [],
        public ?array $rex_info = [],
    ) {
    }
}
