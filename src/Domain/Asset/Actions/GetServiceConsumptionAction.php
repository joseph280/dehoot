<?php

namespace Domain\Asset\Actions;

use Domain\Shared\Actions\Action;
use Domain\Asset\Entities\Service;
use Domain\Asset\Models\StakedAsset;
use Domain\Player\ValueObjects\PlayerServices;
use Domain\Asset\ValueObjects\ServiceConsumption;
use Domain\Asset\Collections\StakedAssetCollection;

class GetServiceConsumptionAction extends Action
{
    public function handle(StakedAssetCollection $stakedAssets): PlayerServices
    {
        $currentWater = 0;
        $currentEnergy = 0;

        $residentialsStakedAssets = $stakedAssets->residentials();
        $servicesStakedAssets = $stakedAssets->services();

        $residentialsStakedAssets->each(function ($stakedAsset) use (&$currentWater, &$currentEnergy) {
            $currentWater += $stakedAsset->data->water;
            $currentEnergy += $stakedAsset->data->energy;
        });

        $totalWater = $this->getTotalAvailable($servicesStakedAssets, Service::WATER_TYPE);
        $totalEnergy = $this->getTotalAvailable($servicesStakedAssets, Service::ENERGY_TYPE);

        return PlayerServices::from(
            ServiceConsumption::from($currentWater, $totalWater),
            ServiceConsumption::from($currentEnergy, $totalEnergy),
        );
    }

    protected function getTotalAvailable(StakedAssetCollection $stakedAssets, string $type): int
    {
        $total = 0;

        $stakedAssets
            ->where('data.type', $type)
            ->each(function (StakedAsset $stakedAsset) use (&$total) {
                $total += $stakedAsset->data->capacity;
            });

        return $total;
    }
}
