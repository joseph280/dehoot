<?php

namespace Domain\Asset\Actions;

use Domain\Player\Models\Player;
use Domain\Shared\Actions\Action;
use Domain\Log\Models\TransactionLog;
use Domain\Shared\ValueObjects\Reward;
use Domain\Log\Enums\TransactionStatus;
use Domain\Shared\Enums\TransactTypeEnum;
use Domain\Player\Repositories\BalancesRepository;
use Domain\Asset\Collections\StakedAssetCollection;

class ClaimAllAction extends Action
{
    public function handle(StakedAssetCollection $stakedAssets, Player $player)
    {
        /** @var Reward */
        $reward = GetRewardAction::execute($stakedAssets, $player);

        if ($reward->total->value > 0) {
            $this->makeTransaction($player, $reward);
        } else {
            $player->transactionLogs()
                ->where('status', TransactionStatus::Processing)
                ->delete();
        }
    }

    protected function makeTransaction(Player $player, Reward $reward)
    {
        /** @var TransactionLog */
        $transactionLog = TransactAction::execute($player, $reward->total, TransactTypeEnum::Reward);

        $assetsClaimed = $reward->stakedAssets;

        if ($assetsClaimed->isNotEmpty() && $transactionLog->status === TransactionStatus::Success) {
            app(BalancesRepository::class)->clearBalances($player);

            $assetsClaimed->toQuery()->update([
                'staked_at' => now(),
            ]);
        }
    }
}
