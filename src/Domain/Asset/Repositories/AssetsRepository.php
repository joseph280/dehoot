<?php

namespace Domain\Asset\Repositories;

use Domain\Asset\Entities\Asset;
use Domain\Player\Models\Player;
use Domain\Shared\Traits\Cacheable;
use Domain\Atomic\Actions\GetAssetsAction;
use Domain\Asset\Collections\AssetCollection;

class AssetsRepository
{
    use Cacheable;

    /**
     * @param Player $player
     *
     * @return AssetCollection<Asset>
     */
    public function getPlayerAssets(Player $player): AssetCollection
    {
        return $this->getFromCache(
            "players/{$player->id}/assets",
            fn () => GetAssetsAction::execute($player, $player->stakedAssets),
        );
    }

    public function clearPlayerAssetsCache(Player $player): void
    {
        $this->clearCache("players/{$player->id}/assets");
    }

    public function getFreshPlayerAssets(Player $player): AssetCollection
    {
        $this->clearPlayerAssetsCache($player);

        return $this->getPlayerAssets($player);
    }
}
