<?php

namespace Domain\Asset\Models;

use Domain\Asset\Entities\Asset;
use Domain\Player\Models\Player;
use Domain\Shared\Models\BaseModel;
use Domain\Asset\Effects\TheHutEffect;
use Domain\Asset\Entities\Residential;
use Domain\Asset\Entities\SpecialBuild;
use Domain\Asset\ValueObjects\Position;
use Domain\Asset\ValueObjects\AssetData;
use Domain\Asset\Effects\MajorPlazaEffect;
use Domain\Asset\Effects\FirstHootBankEffect;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Domain\Asset\Collections\StakedAssetCollection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StakedAsset extends BaseModel
{
    public const BONUS_MULTIPLIER = 0.5;

    public const STAKED_LIMIT = 10;

    protected $fillable = [
        'player_id',
        'asset_id',
        'data',
        'staked_at',
        'claimed_at',
        'land',
        'position_x',
        'position_y',
    ];

    protected $casts = [
        'data' => AssetData::class,
        'staked_at' => 'datetime',
        'claimed_at' => 'datetime',
    ];

    public function newCollection(array $models = []): StakedAssetCollection
    {
        return new StakedAssetCollection($models);
    }

    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class);
    }

    public static function getPlayerAssetsInStaking(string $id): StakedAssetCollection
    {
        return StakedAsset::where('player_id', $id)->get();
    }

    public static function getPlayerResidentialAssetsInStaking(string $id): StakedAssetCollection
    {
        return StakedAsset::where('player_id', $id)->where('data->schema', Residential::SCHEMA_NAME)->get();
    }

    public static function getPlayerSpecialAssetsInStaking(string $id): StakedAssetCollection
    {
        return StakedAsset::where('player_id', $id)->where('data->schema', SpecialBuild::SCHEMA_NAME)->get();
    }

    public static function getStakingLimit(StakedAssetCollection $stakedAssets): int
    {
        $limit = StakedAsset::STAKED_LIMIT;

        if ($stakedAssets->isNotEmpty() && StakedAsset::hasMajorPlaza($stakedAssets)) {
            $limit = MajorPlazaEffect::STAKED_LIMIT;
        }

        return $limit;
    }

    public static function hasMajorPlaza(StakedAssetCollection | StakedAsset $assets): bool
    {
        return self::containsTemplateId($assets, MajorPlazaEffect::TEMPLATE_ID);
    }

    public static function hasFirstHootBank(StakedAssetCollection | StakedAsset $assets): bool
    {
        return self::containsTemplateId($assets, FirstHootBankEffect::TEMPLATE_ID);
    }

    public static function hasTheHut(StakedAssetCollection | StakedAsset $assets): bool
    {
        return self::containsTemplateId($assets, TheHutEffect::TEMPLATE_ID);
    }

    public function asset(): Asset
    {
        return $this->data;
    }

     /**
     * Get the staked asset position
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function position(): Attribute
    {
        return Attribute::make(
            get: fn () => Position::from($this->position_x, $this->position_y),
        );
    }

    private static function containsTemplateId(StakedAssetCollection | StakedAsset $assets, string $template): bool
    {
        $result = false;

        if ($assets instanceof StakedAssetCollection) {
            $result = $assets
                ->pluck('data')
                ->contains('templateId', $template);
        }

        if ($assets instanceof StakedAsset) {
            $result = $assets->data->templateId === $template;
        }

        return $result;
    }
}
