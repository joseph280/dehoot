<?php

namespace Domain\Asset\Actions;

use Domain\Player\Models\Player;
use Domain\Shared\Actions\Action;
use Domain\Log\Models\TransactionLog;
use Domain\Shared\ValueObjects\Token;
use Domain\Log\Enums\TransactionStatus;
use Domain\Log\ValueObjects\ActionData;
use Domain\Shared\Enums\TransactTypeEnum;
use Domain\Log\Enums\TransactionActionName;
use Domain\EosPhp\Support\EosEnvironmentManager;
use Domain\EosPhp\Actions\CheckSuccessfulReceiptAction;
use Domain\EosPhp\Entities\Transaction\TransactionReceipt;

class TransactAction extends Action
{
    public function handle(Player $player, Token $quantity, TransactTypeEnum $transactType): TransactionLog
    {
        /** @var EosEnvironmentManager */
        $eosEnv = app(EosEnvironmentManager::class);
        $actionName = TransactionActionName::Transfer->value;

        $actionData = new ActionData(
            from: $eosEnv->getContractAccount(),
            to: $eosEnv->getTransferReceiverAccount($player),
            quantity: $quantity->formattedWithToken,
            memo: $actionName,
        );

        /** @var TransactionReceipt */
        $receipt = TransferTokenAction::execute($actionData, $transactType);
        $successfulReceipt = CheckSuccessfulReceiptAction::execute($receipt);

        /** @var TransactionLog */
        $transactionLog = $player->transactionLogs()
            ->where('status', TransactionStatus::Processing)
            ->first();

        $transactionLog->update(
            [
                'transaction_id' => $receipt->transaction_id,
                'action_name' => $actionName,
                'action_data' => $actionData,
                'status' => $successfulReceipt
                    ? TransactionStatus::Success
                    : TransactionStatus::Failed,
                'amount' => $quantity->formattedWithToken,
            ]
        );

        return $transactionLog;
    }
}
