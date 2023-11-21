<?php

namespace Domain\Player\Repositories;

use Domain\Player\Models\Player;
use Domain\Shared\Traits\Cacheable;
use Domain\Player\Actions\GetWaxBalanceAction;
use Domain\Player\ValueObjects\PlayerBalances;
use Domain\Player\Actions\GetHootBalanceAction;

class BalancesRepository
{
    use Cacheable;

    public const CACHED_TIME = 300;

    public function getBalances(Player $player): PlayerBalances
    {
        return $this->getFromCache(
            "players/{$player->id}/balances",
            function () use ($player) {
                return PlayerBalances::from(
                    GetHootBalanceAction::execute($player),
                    GetWaxBalanceAction::execute($player)
                );
            },
            self::CACHED_TIME,
        );
    }

    public function clearBalances(Player $player): void
    {
        $this->clearCache("players/{$player->id}/balances");
    }

    public function getFreshPlayerAssets(Player $player): PlayerBalances
    {
        $this->clearBalances($player);

        return $this->getBalances($player);
    }
}
