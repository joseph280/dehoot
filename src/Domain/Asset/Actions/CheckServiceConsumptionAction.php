<?php

namespace Domain\Asset\Actions;

use Domain\Asset\Entities\Asset;
use Domain\Shared\Actions\Action;
use Domain\Asset\Entities\Service;
use Domain\Asset\Entities\SpecialBuild;
use Domain\Player\ValueObjects\PlayerServices;
use Domain\Asset\Collections\StakedAssetCollection;

class CheckServiceConsumptionAction extends Action
{
    public function handle(StakedAssetCollection $stakedAssets, Asset $asset): bool
    {
        if ($asset->schema === SpecialBuild::SCHEMA_NAME) {
            return true;
        }

        if ($asset->schema === Service::SCHEMA_NAME) {
            return true;
        }

        /** @var PlayerServices */
        $consumption = GetServiceConsumptionAction::execute($stakedAssets);

        $isWaterAllowed = $this->isUnderConsumptionLimit($asset->water, $consumption->water->current, $consumption->water->total);
        $isEnergyAllowed = $this->isUnderConsumptionLimit($asset->energy, $consumption->energy->current, $consumption->energy->total);

        return $isEnergyAllowed && $isWaterAllowed;
    }

    public function isUnderConsumptionLimit(int $consumption, int $currentConsumption, int $total): bool
    {
        return ($consumption + $currentConsumption) <= $total;
    }
}
