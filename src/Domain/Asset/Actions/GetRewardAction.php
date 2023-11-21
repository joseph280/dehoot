<?php

namespace Domain\Asset\Actions;

use Domain\Player\Models\Player;
use Domain\Shared\Actions\Action;
use Domain\Asset\Models\StakedAsset;
use Domain\Shared\ValueObjects\Token;
use Domain\Asset\Entities\Residential;
use Domain\Shared\ValueObjects\Reward;
use Illuminate\Database\Eloquent\Collection;
use Domain\Asset\Collections\StakedAssetCollection;

class GetRewardAction extends Action
{
    protected StakedAssetCollection $stakedAssetWithReward;

    protected mixed $effects;

    public function handle(StakedAssetCollection | StakedAsset $stakedAssets, Player $player): Reward
    {
        $staked = 0;

        $this->stakedAssetWithReward = new StakedAssetCollection([]);

        if ($stakedAssets instanceof StakedAsset) {
            $stakedAssets = new StakedAssetCollection([$stakedAssets]);
        }

        /** Recompensa base: Time en staking por población de todos los stakedAssets = número flotante */
        $staked += $this->calculateStakedByTime($stakedAssets, $player);

        /** Calcular los efectos en staking | Retorar bonus*/
        $effectBonuses = $this->calculateEffectBonus($stakedAssets, $player);

        /** @var float Otro bonus por VIP */
        $bonus = CalculateVIPBonusAction::execute($staked, $player->is_vip);

        $totalSum = $staked + $effectBonuses + $bonus;

        /** @var Token Calcular el impuesto de la sumatoria */
        $tax = CalculateTaxAction::execute(Token::from($totalSum));

        $multiplier = config('dehoot.reward_multiplier');

        /** Recompensa final, sumatoria menos impuestos */
        $total = ($totalSum - $tax->value) * $multiplier;

        return new Reward(
            staked: Token::from($staked),
            bonus: Token::from($bonus),
            tax: $tax,
            total: Token::from($total),
            stakedAssets: $this->stakedAssetWithReward,
            effects: $this->effects,
        );
    }

    protected function calculateEffectBonus(StakedAssetCollection $stakedAssets, Player $player): float
    {
        $effectsValueArray = SpecialEffectBonusAction::execute($stakedAssets, $player);
        /** @var Collection */
        $this->effects = data_get($effectsValueArray, 'effects');
        $effectAssets = data_get($effectsValueArray, 'assets');
        $effectAssets->each(fn ($asset) => $this->stakedAssetWithReward->push($asset));

        return $this->effects->sum(fn ($effect) => $effect->bonus->value);
    }

    protected function calculateStakedByTime(Collection $stakedAssets, Player $player): float
    {
        $staked = 0;

        $stakedAssets->each(function ($asset) use (&$staked, $player) {
            if ($asset->data->schema === Residential::SCHEMA_NAME) {
                $staked += $this->calculateStaked($asset, $player);
            }
        });

        return $staked;
    }

    protected function calculateStaked(StakedAsset $stakedAsset, Player $player)
    {
        /** @var Token */
        $assetStakedAmount = CalculateBaseRewardAction::execute($stakedAsset, $player->is_vip);

        /** @var bool */
        $assetHasReward = CheckRewardAction::execute($stakedAsset, $assetStakedAmount);

        if ($assetHasReward) {
            $this->stakedAssetWithReward->add($stakedAsset);
        }

        return $assetHasReward ? $assetStakedAmount->value : 0;
    }
}
