<?php

namespace Domain\Atomic\Actions;

use Exception;
use Domain\Asset\Entities\Asset;
use Domain\Player\Models\Player;
use Domain\Shared\Actions\Action;
use Domain\Atomic\Api\AtomicApiManager;
use Domain\Asset\Collections\AssetCollection;
use Domain\Asset\Collections\StakedAssetCollection;
use Domain\Atomic\Interfaces\AtomicApiManagerInterface;

class GetAssetsAction extends Action
{
    /**
     * @param Player $player
     * @param StakedAssetCollection $stakedAssets
     *
     * @throws Exception
     *
     * @return AssetCollection<Asset>
     */
    public function handle(Player $player, StakedAssetCollection $stakedAssets): AssetCollection
    {
        /** @var AtomicApiManager */
        $atomicAPI = app(AtomicApiManagerInterface::class);

        $atomicAssetsResponse = $atomicAPI->assets(
            player: $player,
        );

        $data = data_get($atomicAssetsResponse, 'data');

        if (! is_array($data)) {
            $this->generateExceptionMessage($atomicAssetsResponse);
        }

        OrphanStakedAction::execute($stakedAssets, $data);

        return FilterAssetsAction::execute($atomicAssetsResponse);
    }

    /**
     * @throws Exception
     */
    private function generateExceptionMessage(array | null $response = null)
    {
        $data = json_encode($response);

        throw new Exception(
            "There was a problem requesting the assets from Atomic, this was returned: ${data}"
        );
    }
}
