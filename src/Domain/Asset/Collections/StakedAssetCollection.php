<?php

namespace Domain\Asset\Collections;

use Illuminate\Support\Carbon;
use Domain\Asset\Entities\Service;
use Domain\Asset\Models\StakedAsset;
use Domain\Asset\Entities\Residential;
use Domain\Asset\Entities\SpecialBuild;
use Domain\Asset\Effects\MajorPlazaEffect;
use Illuminate\Database\Eloquent\Collection;

class StakedAssetCollection extends Collection
{
    public function residentials(): self
    {
        return $this->where('data.schema', Residential::SCHEMA_NAME);
    }

    public function services(): self
    {
        return $this->where('data.schema', Service::SCHEMA_NAME);
    }

    public function specials(): self
    {
        return $this->where('data.schema', SpecialBuild::SCHEMA_NAME);
    }

    public function whereStakedAtOlderThanDate(Carbon $date): self
    {
        return $this->where('staked_at', '<', $date);
    }

    public function whereStakedAtOlderThanOneDay(): self
    {
        return $this->where('staked_at', '<', now()->subDay());
    }

    public function haveReachedStakedLimit(): bool
    {
        return $this->residentials()->count() >= $this->stakingLimit();
    }

    public function stakingLimit(): int
    {
        $limit = StakedAsset::STAKED_LIMIT;

        if ($this->isNotEmpty() && $this->containsTemplateId(MajorPlazaEffect::TEMPLATE_ID)) {
            $limit = MajorPlazaEffect::STAKED_LIMIT;
        }

        return $limit;
    }

    public function getTotalPopulation(): int
    {
        $population = 0;

        $this->each(function (StakedAsset $stakedAsset) use (&$population) {
            $population += $stakedAsset->data->population ?? 0;
        });

        return $population;
    }

    public function isAssetInStaking($assetId): bool
    {
        return $this
            ->contains('asset_id', '=', $assetId);
    }

    public function containsTemplateId(string $templateId): bool
    {
        return $this
            ->pluck('data')
            ->contains('templateId', $templateId);
    }
}
