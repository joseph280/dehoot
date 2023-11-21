<?php

namespace Domain\Player\Actions;

use Domain\Player\Models\Player;
use Domain\Shared\Actions\Action;
use Illuminate\Support\Collection;
use Domain\Asset\Effects\TheHutEffect;
use Domain\Asset\Effects\FirstHootBank2;
use Domain\Asset\Effects\MajorPlazaEffect;
use Domain\Asset\Collections\AssetCollection;
use Domain\Asset\Effects\FirstHootBankEffect;

class IsUserVIPAction extends Action
{
    public function handle(Player $player, AssetCollection $assets): bool
    {
        $specialBuildings = $assets->whereIn(
            'templateId',
            [
                TheHutEffect::TEMPLATE_ID,
                FirstHootBankEffect::TEMPLATE_ID,
                FirstHootBank2::TEMPLATE_ID,
                MajorPlazaEffect::TEMPLATE_ID,
            ]
        );

        return $this->playerHasSpecialBuildings($specialBuildings);
    }

    private function playerHasSpecialBuildings(Collection $specialBuildings): bool
    {
        $hasMajorPlaza = $specialBuildings->contains('templateId', MajorPlazaEffect::TEMPLATE_ID);

        $hasFirstHootBank = $specialBuildings->contains(function ($item, $key) {
            return $item->templateId === FirstHootBankEffect::TEMPLATE_ID ||
            $item->templateId === FirstHootBank2::TEMPLATE_ID;
        });

        $hasTheHut = $specialBuildings->contains('templateId', TheHutEffect::TEMPLATE_ID);

        return $hasMajorPlaza && $hasFirstHootBank && $hasTheHut;
    }
}
