<?php

namespace Domain\Shared\ValueObjects;

use Illuminate\Support\Collection;
use Domain\Asset\Collections\StakedAssetCollection;

class Reward
{
    public function __construct(
        public Token $staked,
        public Token $bonus,
        public Token $tax,
        public Token $total,
        public StakedAssetCollection $stakedAssets,
        public ?Collection $effects = null,
        public ?Token $stakedWithBonus = null,
    ) {
        $this->stakedWithBonus = $stakedWithBonus ?? Token::from($this->staked->value + $this->bonus->value);
    }
}
