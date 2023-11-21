<?php

namespace Domain\Atomic\Actions;

use Domain\Shared\Actions\Action;
use Illuminate\Support\Collection;
use Domain\Asset\Models\StakedAsset;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

class OrphanStakedAction extends Action
{
    public function handle(EloquentCollection $stakedAssets, array $assetsResponseData)
    {
        /** @var Collection */
        $assetsResponseId = collect($assetsResponseData)->pluck('asset_id');

        /** @var Collection */
        $stakedAssetsId = $stakedAssets->pluck('asset_id');

        $assetIdsDifference = $stakedAssetsId->diff($assetsResponseId);

        if ($assetIdsDifference->isNotEmpty()) {
            $this->forgetAssetOnStakedAsset($stakedAssets, $assetIdsDifference);
            StakedAsset::query()->whereIn('asset_id', $assetIdsDifference)->delete();
        }
    }

    private function forgetAssetOnStakedAsset(EloquentCollection $stakedAssets, Collection $assetsDifference)
    {
        $stakedAssets->each(function ($item, $key) use ($assetsDifference, $stakedAssets) {
            if ($assetsDifference->contains($item->asset_id)) {
                $stakedAssets->forget($key);
            }
        });
    }
}
