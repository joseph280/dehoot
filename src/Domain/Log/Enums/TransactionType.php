<?php

namespace Domain\Log\Enums;

enum TransactionType: string
{
    case ClaimAll = 'CLAIM ALL';

    case Unstake = 'UNSTAKE';
}
