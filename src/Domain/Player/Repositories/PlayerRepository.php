<?php

namespace Domain\Player\Repositories;

use Domain\Player\Models\Player;
use Domain\Shared\Traits\Cacheable;
use Domain\Player\Actions\IsUserVIPAction;
use Domain\Asset\Collections\AssetCollection;
use Domain\Asset\Repositories\AssetsRepository;

class PlayerRepository
{
    use Cacheable;

    public const CACHED_TIME = 300;

    public function getIsVIP(Player $player): bool
    {
        /** @var AssetCollection */
        $assetCollection = app(AssetsRepository::class)->getPlayerAssets($player);

        return $this->getFromCache(
            "players/{$player->id}/is_vip",
            fn () => IsUserVIPAction::execute($player, $assetCollection),
            self::CACHED_TIME,
        );
    }
}
