<?php

namespace Domain\Asset\Actions;

use Domain\Shared\Actions\Action;
use Domain\Asset\Models\StakedAsset;
use Domain\Shared\ValueObjects\Token;

class CheckRewardAction extends Action
{
    public function handle(StakedAsset $stakedAsset, Token $reward): bool
    {
        $pastDate = (! $stakedAsset->staked_at->isToday() && $stakedAsset->staked_at->isPast());

        if ($reward->value > 0 && $pastDate) {
            return true;
        }

        return false;
    }
}
