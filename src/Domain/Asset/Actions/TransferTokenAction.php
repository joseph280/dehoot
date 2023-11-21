<?php

namespace Domain\Asset\Actions;

use Domain\Shared\Actions\Action;
use Domain\Log\ValueObjects\ActionData;
use Domain\Shared\Enums\TransactTypeEnum;
use Domain\EosPhp\Interfaces\EosApiInterface;
use Domain\EosPhp\Support\EosEnvironmentManager;
use Domain\EosPhp\Entities\Action\TransactAction;
use Domain\EosPhp\Entities\Transaction\Transaction;
use Domain\EosPhp\Entities\Transaction\TransactionConfig;
use Domain\EosPhp\Entities\Transaction\TransactionReceipt;

class TransferTokenAction extends Action
{
    public function handle(ActionData $actionData, TransactTypeEnum $transactType): TransactionReceipt
    {
        /** @var EosApiInterface */
        $eosApi = app(EosApiInterface::class);
        /** @var EosEnvironmentManager */
        $eosEnv = app(EosEnvironmentManager::class);

        $getContractAccount = match ($transactType) {
            TransactTypeEnum::Reward => $eosEnv->getContractAccount(),
        };

        $action = new TransactAction(
            account: $getContractAccount,
            name: 'transfer',
            authorization: [[
                'actor' => $getContractAccount,
                'permission' => 'active',
            ]],
            data: [
                'from' => $actionData->from,
                'to' => $actionData->to,
                'quantity' => $actionData->quantity,
                'memo' => $actionData->memo,
            ]
        );

        $transaction = new Transaction(
            actions: [$action]
        );

        $transactionConfig = new TransactionConfig(
            blocksBehind: 3,
            expireSeconds: 1200,
        );

        return $eosApi->transact($transaction, $transactionConfig);
    }
}
