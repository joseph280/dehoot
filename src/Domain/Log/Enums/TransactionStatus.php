<?php

namespace Domain\Log\Enums;

enum TransactionStatus: string
{
    case Success = 'SUCCESS';

    case Processing = 'PROCESSING';

    case Failed = 'FAILED';
}
