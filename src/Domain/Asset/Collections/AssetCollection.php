<?php

namespace Domain\Asset\Collections;

use Exception;
use Domain\Asset\Entities\Asset;
use Domain\Player\Models\Player;
use Domain\Asset\Entities\Service;
use Illuminate\Support\Collection;
use Domain\Asset\Models\StakedAsset;
use Domain\Asset\Entities\Residential;
use Domain\Asset\Entities\SpecialBuild;
use Domain\Asset\ValueObjects\Dimension;
use Domain\Asset\Actions\CalculateAssetRewardAction;

class AssetCollection extends Collection
{
    /**
     * @throws Exception
     */
    public function firstWhereAssetId(string $assetId): Asset
    {
        $asset = $this->firstWhere(fn (Asset $asset) => $asset->assetId === $assetId);

        if (! $asset) {
            throw new Exception("The user doesnt own the asset with id: ${assetId}");
        }

        return $asset;
    }

    public function residentialBuildings(): self
    {
        return $this->where('schema', Residential::SCHEMA_NAME)->values();
    }

    public function specialBuildings(): self
    {
        return $this->where('schema', SpecialBuild::SCHEMA_NAME)->values();
    }

    public function serviceBuildings(): self
    {
        return $this->where('schema', Service::SCHEMA_NAME)->values();
    }

    public function whereOnStaking(bool $onStaking = true, ?StakedAssetCollection $stakedAssets = null): self
    {
        if ($this->count() === 0) {
            return $this;
        }

        /** @var Asset */
        $asset = $this->first();

        if ($asset->staking === null) {
            if (! $stakedAssets) {
                throw new Exception('You need staked assets to use whereOnStaking');
            }
            $this->updateStaking($stakedAssets);
        }

        return $this->where('staking', $onStaking)->values();
    }

    public function loadOnStaking(StakedAssetCollection $stakedAssets): self
    {
        return $this->map(function ($asset) use ($stakedAssets) {
            $asset->staking = $stakedAssets->isAssetInStaking($asset->assetId);

            if ($asset->staking) {
                /** @var StakedAsset */
                $stakedAsset = $stakedAssets->firstWhere('asset_id', $asset->assetId);
                $asset->position_x = $stakedAsset->position->x;
                $asset->position_y = $stakedAsset->position->y;
            }

            return $asset;
        });
    }

    public function loadStakedBalance(Player $player): self
    {
        return $this->map(function ($asset) use ($player) {
            if ($asset->staking) {
                $stakedAsset = $player->stakedAssets->firstWhere('asset_id', $asset->assetId);
                $asset->stakedBalance = CalculateAssetRewardAction::execute($stakedAsset, $player->is_vip);
            }

            return $asset;
        });
    }

    public function loadRowsAndColumns(): self
    {
        return $this->map(function (Asset $asset) {
            /** @var Dimension */
            $dimensions = $asset->getDimensions($asset->templateId);
            $asset->rows = $dimensions->rows;
            $asset->columns = $dimensions->columns;

            return $asset;
        });
    }
}
