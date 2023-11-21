<?php

namespace Domain\Atomic\Actions;

use Domain\Shared\Actions\Action;
use Domain\Asset\Entities\Service;
use Domain\Asset\Entities\Residential;
use Domain\Asset\Entities\SpecialBuild;
use Domain\Asset\Collections\AssetCollection;

class FilterAssetsAction extends Action
{
    public function handle(array $atomicAssetsResponse): AssetCollection
    {
        return (new AssetCollection(data_get($atomicAssetsResponse, 'data', [])))
            ->map(function (array $asset) {
                $schema = data_get($asset, 'schema.schema_name');
                $type = data_get($asset, 'data.type');

                if ($schema === Residential::SCHEMA_NAME) {
                    return $this->makeResidential($asset);
                }

                if ($schema === SpecialBuild::SCHEMA_NAME) {
                    return $this->makeSpecialBuilding($asset);
                }

                if ($schema === Service::SCHEMA_NAME && ($type === Service::WATER_TYPE || $type === SERVICE::ENERGY_TYPE)) {
                    return $this->makeService($asset);
                }
            })->whereNotNull();
    }

    public function makeService(array $data): Service
    {
        return new Service(
            assetId: data_get($data, 'asset_id'),
            templateId: data_get($data, 'template.template_id'),
            schema: data_get($data, 'schema.schema_name'),
            owner: data_get($data, 'owner'),
            imgUrl: data_get($data, 'data.img', ''),
            name: data_get($data, 'data.name', ''),
            capacity: (int) data_get($data, 'data.Capacity'),
            type: data_get($data, 'data.type'),
            description: data_get($data, 'data.description'),
            season: data_get($data, 'data.season'),
        );
    }

    public function makeResidential(array $data): Residential
    {
        $population = data_get($data, 'data.Population');

        return new Residential(
            assetId: data_get($data, 'asset_id'),
            templateId: data_get($data, 'template.template_id'),
            schema: data_get($data, 'schema.schema_name'),
            owner: data_get($data, 'owner'),
            imgUrl: data_get($data, 'data.img', ''),
            name: data_get($data, 'data.name', ''),
            description: data_get($data, 'data.Description', ''),
            type: data_get($data, 'data.Type'),
            population: (int) $population,
            water: (int) data_get($data, 'data.Water', $population),
            energy: (int) data_get($data, 'data.Energy', $population),
            level: data_get($data, 'data.Level'),
            season: data_get($data, 'data.Season'),
        );
    }

    public function makeSpecialBuilding(array $data): SpecialBuild
    {
        return new SpecialBuild(
            assetId: data_get($data, 'asset_id'),
            templateId: data_get($data, 'template.template_id'),
            schema: data_get($data, 'schema.schema_name'),
            owner: data_get($data, 'owner'),
            imgUrl: data_get($data, 'data.img', ''),
            name: data_get($data, 'data.name', ''),
            description: data_get($data, 'data.Description', ''),
            type: data_get($data, 'data.Type'),
            season: data_get($data, 'data.Season'),
        );
    }
}
