<?php

namespace Domain\Asset\Actions;

use Domain\Shared\Actions\Action;
use Domain\Asset\Models\StakedAsset;

class CalculateVIPBonusAction extends Action
{
    public function handle(float $currentReward, bool $isPlayerVIP): float
    {
        return $isPlayerVIP ? $currentReward *= StakedAsset::BONUS_MULTIPLIER : 0;
    }
}
