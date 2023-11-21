<?php

namespace Domain\EosPhp\Actions;

use Domain\Shared\Actions\Action;
use Domain\EosPhp\Entities\Transaction\TransactionReceipt;

class CheckSuccessfulReceiptAction extends Action
{
    public function handle(TransactionReceipt $receipt): bool
    {
        $state = data_get($receipt, 'processed.receipt.status');

        return $state === 'executed';
    }
}
