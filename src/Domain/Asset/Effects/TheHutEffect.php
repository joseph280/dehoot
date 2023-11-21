<?php

namespace Domain\Asset\Effects;

use Domain\Player\Models\Player;
use Illuminate\Support\Collection;
use Domain\Asset\Models\StakedAsset;
use Domain\Asset\Entities\Residential;
use Domain\Asset\Actions\GetTimeDurationInDaysAction;

class TheHutEffect
{
    public const TEMPLATE_ID = '431591';

    public const NAME = 'The Hut';

    private const DAILY_BONUS = 50;

    private const MAXIMUM_ASSET = 10;

    public static function effect(StakedAsset $hut, Collection $stakedAssets): array
    {
        $hutStakingDays = GetTimeDurationInDaysAction::execute($hut->staked_at, now());
        $bonus = 0;
        $assetsCounter = 0;

        $stakedAssets = $stakedAssets->where('data.schema', Residential::SCHEMA_NAME)
            ->where('staked_at', '<=', now()->subDay())
            ->sortBy('staked_at');

        $stakedAssets->each(function ($asset) use (&$bonus, &$assetsCounter, $hutStakingDays) {
            $assetStakingDays = GetTimeDurationInDaysAction::execute($asset->staked_at, now());

            if ($assetsCounter < self::MAXIMUM_ASSET) {
                if ($assetStakingDays === $hutStakingDays) {
                    $bonus += $hutStakingDays * self::DAILY_BONUS;
                    $assetsCounter++;
                }

                if ($hutStakingDays > $assetStakingDays) {
                    $bonus += ($hutStakingDays - $assetStakingDays) * self::DAILY_BONUS;
                    $assetsCounter++;
                }
            }

            if ($assetsCounter === self::MAXIMUM_ASSET) {
                return false;
            }
        });

        return [
            'bonus' => $bonus,
            'assets' => collect([$hut]),
        ];
    }

    public static function getOldestHut(Player $player): StakedAsset | null
    {
        return StakedAsset::query()
            ->oldest('staked_at')
            ->where('player_id', $player->id)
            ->firstWhere('data->templateId', TheHutEffect::TEMPLATE_ID);
    }

    public static function isTheHut(string $templateId)
    {
        return $templateId === self::TEMPLATE_ID;
    }
}
