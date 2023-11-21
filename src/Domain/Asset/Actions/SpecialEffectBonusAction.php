<?php

namespace Domain\Asset\Actions;

use Domain\Player\Models\Player;
use Domain\Shared\Actions\Action;
use Illuminate\Support\Collection;
use Domain\Asset\Models\StakedAsset;
use Domain\Asset\Effects\TheHutEffect;
use Domain\Asset\Effects\FirstHootBank2;
use Domain\Asset\Effects\MajorPlazaEffect;
use Domain\Asset\ValueObjects\EffectValue;
use Domain\Asset\Effects\FirstHootBankEffect;
use Domain\Asset\Collections\StakedAssetCollection;

class SpecialEffectBonusAction extends Action
{
    private Collection $effects;

    private Collection $assets;

    public function handle(StakedAssetCollection | StakedAsset $assetsToClaim, Player $player, ?Collection $stakedAssets = null): array
    {
        $stakedAssets = $stakedAssets ?? StakedAsset::getPlayerAssetsInStaking($player->id);
        $this->effects = collect([]);
        $this->assets = collect([]);

        if ($assetsToClaim instanceof StakedAssetCollection) {
            $this->makeEffectsForCollection($assetsToClaim, $stakedAssets, $player);
        }

        if ($assetsToClaim instanceof StakedAsset) {
            $this->makeEffectForStakedAsset($assetsToClaim, $stakedAssets, $player);
        }

        return [
            'effects' => $this->effects,
            'assets' => $this->assets,
        ];
    }

    private function addEffect(string $name, array $effect)
    {
        $bonus = data_get($effect, 'bonus', 0);
        $assets = data_get($effect, 'assets', collect([]));

        if ($bonus > 0) {
            $this->effects->push(EffectValue::from($name, $bonus));

            if ($assets->isNotEmpty()) {
                $assets->each(fn ($asset) => $this->assets->push($asset));
            }
        }
    }

    private function makeEffectForStakedAsset(StakedAsset $assetToClaim, StakedAssetCollection $stakedAssets, Player $player)
    {
        if (MajorPlazaEffect::isMajorPlaza($assetToClaim->data->templateId)) {
            $this->addEffect(MajorPlazaEffect::NAME, MajorPlazaEffect::effect($assetToClaim));
        }

        if (FirstHootBankEffect::isFirstHootBank($assetToClaim->data->templateId)) {
            $assetAsCollection = collect([$assetToClaim]);
            $this->addEffect(FirstHootBankEffect::NAME, FirstHootBankEffect::effect($assetAsCollection, $player));
        }

        if (FirstHootBank2::isFirstHootBank($assetToClaim->data->templateId)) {
            $assetAsCollection = collect([$assetToClaim]);
            $this->addEffect(FirstHootBank2::NAME, FirstHootBank2::effect($assetAsCollection, $player));
        }

        if (TheHutEffect::isTheHut($assetToClaim->data->templateId)) {
            $this->addEffect(TheHutEffect::NAME, TheHutEffect::effect($assetToClaim, $stakedAssets));
        }

        if (StakedAsset::hasTheHut($stakedAssets) && ! TheHutEffect::isTheHut($assetToClaim->data->templateId)) {
            $hut = TheHutEffect::getOldestHut($player);
            $assetAsCollection = collect([$assetToClaim]);
            $this->addEffect(TheHutEffect::NAME, TheHutEffect::effect($hut, $assetAsCollection));
        }
    }

    private function makeEffectsForCollection(StakedAssetCollection $assetsToClaim, StakedAssetCollection $stakedAssets, Player $player)
    {
        if ($assetsToClaim->isNotEmpty() && $stakedAssets->isNotEmpty()) {
            /** @var StakedAsset */
            $majorPlaza = MajorPlazaEffect::getOldestMajorPlaza($player);

            $hut = TheHutEffect::getOldestHut($player);

            $banks = $stakedAssets->where('data.templateId', FirstHootBankEffect::TEMPLATE_ID);

            /** @var Collection */
            $banks2 = $stakedAssets->where('data.templateId', FirstHootBank2::TEMPLATE_ID);

            if ($majorPlaza) {
                $this->addEffect(MajorPlazaEffect::NAME, MajorPlazaEffect::effect($majorPlaza));
            }

            if ($banks->isNotEmpty()) {
                $this->addEffect(FirstHootBankEffect::NAME, FirstHootBankEffect::effect($banks, $player));
            }

            if ($banks2->isNotEmpty()) {
                $this->addEffect(FirstHootBank2::NAME, FirstHootBank2::effect($banks2, $player));
            }

            if ($hut) {
                $this->addEffect(TheHutEffect::NAME, TheHutEffect::effect($hut, $stakedAssets));
            }
        }
    }
}
