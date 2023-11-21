<?php

namespace Domain\Asset\Actions;

use Domain\Player\Models\Player;
use Domain\Shared\Actions\Action;
use Domain\Asset\Models\StakedAsset;
use Domain\Shared\ValueObjects\Reward;
use Domain\Log\Enums\TransactionStatus;
use Domain\Shared\Enums\TransactTypeEnum;
use Domain\Player\Repositories\BalancesRepository;

class UnstakeAction extends Action
{
    public function handle(StakedAsset $stakedAsset, Player $player)
    {
        /** @var Reward */
        $reward = GetRewardAction::execute($stakedAsset, $player);

        if (CheckRewardAction::execute($stakedAsset, $reward->total)) {
            TransactAction::execute($player, $reward->total, TransactTypeEnum::Reward);
        } else {
            $player->transactionLogs()
                ->where('status', TransactionStatus::Processing)
                ->delete();
        }

        $stakedAsset->delete();

        app(BalancesRepository::class)->clearBalances($player);
    }
}
