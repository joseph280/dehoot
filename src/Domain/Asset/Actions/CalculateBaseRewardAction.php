<?php

namespace Domain\Asset\Actions;

use Domain\Shared\Actions\Action;
use Domain\Asset\Models\StakedAsset;
use Domain\Shared\ValueObjects\Token;

class CalculateBaseRewardAction extends Action
{
    public function handle(StakedAsset $stakedAsset): Token
    {
        /** @var int $amount
         * It refers to the time duration of past days */
        $amount = GetTimeDurationInDaysAction::execute($stakedAsset->staked_at, now());

        /** @var int $population
         * It refers to asset's population  */
        $population = $stakedAsset->data->population;

        $amount = $amount * $population;

        return Token::from($amount);
    }
}
