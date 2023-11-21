<?php

namespace Domain\Asset\Effects;

use Domain\Player\Models\Player;
use Domain\Asset\Models\StakedAsset;
use Domain\Asset\Entities\Residential;
use Domain\Asset\Collections\StakedAssetCollection;
use Domain\Asset\Actions\GetTimeDurationInDaysAction;

class MajorPlazaEffect
{
    public const STAKED_LIMIT = 20;

    public const TEMPLATE_ID = '442223';

    public const NAME = 'Major Plaza';

    private const DAILY_BONUS = 250;

    public static function effect(StakedAsset $majorPlaza): array
    {
        $daysInStaking = GetTimeDurationInDaysAction::execute($majorPlaza->staked_at, now());

        return [
            'bonus' => $daysInStaking * self::DAILY_BONUS,
            'assets' => collect([$majorPlaza]),
        ];
    }

    public function canBeUnstaked(StakedAssetCollection $stakedAssets): bool
    {
        $value = false;

        $residencialCount = $stakedAssets->where('data.schema', Residential::SCHEMA_NAME)->count();

        if ($residencialCount < StakedAsset::STAKED_LIMIT) {
            $value = true;
        }

        return $value;
    }

    public static function getOldestMajorPlaza(Player $player): StakedAsset | null
    {
        return StakedAsset::query()
            ->oldest('staked_at')
            ->where('player_id', $player->id)
            ->firstWhere('data->templateId', MajorPlazaEffect::TEMPLATE_ID);
    }

    public static function isMajorPlaza(string $template_id): bool
    {
        return self::TEMPLATE_ID === $template_id;
    }
}
