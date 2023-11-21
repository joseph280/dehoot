<?php

namespace Domain\Atomic\Actions;

use Exception;
use Domain\Asset\Entities\Asset;
use Domain\Player\Models\Player;
use Domain\Shared\Actions\Action;
use Domain\Atomic\Api\AtomicApiManager;
use Domain\Asset\ValueObjects\AssetData;
use Domain\Atomic\Interfaces\AtomicApiManagerInterface;

class GetAssetAction extends Action
{
    /** @return Asset */
    public function handle(Player $player, string $templateId): AssetData
    {
        /** @var AtomicApiManager */
        $atomicAPI = app(AtomicApiManagerInterface::class);

        $assetsResponse = $atomicAPI->asset(
            player: $player,
            templateId: $templateId,
        );

        if (empty($assetsResponse['data'])) {
            throw new Exception("The asset doesn't exist");
        }

        return new AssetData(
            templateId: data_get($assetsResponse, 'data.0.template.template_id'),
            schema: data_get($assetsResponse, 'data.0.schema.schema_name'),
            population: data_get($assetsResponse, 'data.0.data.Population'),
            water: data_get($assetsResponse, 'data.0.data.Water'),
            energy: data_get($assetsResponse, 'data.0.data.Energy'),
        );
    }
}
