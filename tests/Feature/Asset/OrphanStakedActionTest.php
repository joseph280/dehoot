<?php

namespace Tests\Feature\Asset;

use Tests\TestCase;
use Domain\Player\Models\Player;
use Domain\Asset\Models\StakedAsset;
use Illuminate\Auth\Authenticatable;
use Domain\Atomic\Actions\OrphanStakedAction;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OrphanStakedActionTest extends TestCase
{
    use RefreshDatabase;

    protected Player $player;

    /**
     * @var StakedAsset|StakedAsset[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Factories\HasFactory|\Illuminate\Database\Eloquent\Model|\LaravelIdea\Helper\Domain\Asset\Models\_IH_StakedAsset_C|mixed
     */
    protected StakedAsset $stakedAsset;

    public function setUp(): void
    {
        parent::setUp();

        /** @var Player|Authenticatable */
        $this->player = Player::factory()->create();

        $this->residential = $this->regularResponse->residential;
    }

    /** @test */
    public function it_deletes_orphan_staked_assets()
    {
        $orphanStakedAsset1 = StakedAsset::factory()->create([
            'player_id' => $this->player->id,
            'asset_id' => '1099668471860',
            'staked_at' => now()->subDay(),
        ]);

        $orphanStakedAsset2 = StakedAsset::factory()->create([
            'player_id' => $this->player->id,
            'asset_id' => '1099668471859',
            'staked_at' => now()->subDay(),
        ]);

        $playerStakedAssets = StakedAsset::getPlayerAssetsInStaking($this->player->id);

        OrphanStakedAction::execute($playerStakedAssets, $this->atomicResponse['data']);

        $this->assertModelMissing($orphanStakedAsset1);
        $this->assertModelMissing($orphanStakedAsset2);
    }

    /** @test */
    public function it_deletes_a_single_orphan_staked_asset()
    {
        $orphanStakedAsset = StakedAsset::factory()->create([
            'player_id' => $this->player->id,
            'asset_id' => '1099668471860',
            'staked_at' => now()->subDay(),
        ]);

        $playerStakedAssets = StakedAsset::getPlayerAssetsInStaking($this->player->id);

        OrphanStakedAction::execute($playerStakedAssets, data_get($this->atomicResponse, 'data'));

        $this->assertModelMissing($orphanStakedAsset);
    }

    /** @test */
    public function has_no_orphan_staked_assets_to_delete()
    {
        $this->stakedAsset = StakedAsset::factory()->create([
            'player_id' => $this->player->id,
            'asset_id' => $this->residential->assetId,
            'staked_at' => now()->subDay(),
        ]);

        $playerStakedAssets = StakedAsset::getPlayerAssetsInStaking($this->player->id);

        OrphanStakedAction::execute($playerStakedAssets, data_get($this->atomicResponse, 'data'));

        $this->assertModelExists($this->stakedAsset);
    }

    /** @test */
    public function has_no_assets_on_atomic_response()
    {
        $this->stakedAsset = StakedAsset::factory()->create([
            'player_id' => $this->player->id,
            'asset_id' => $this->residential->assetId,
            'staked_at' => now()->subDay(),
        ]);

        $playerStakedAssets = StakedAsset::getPlayerAssetsInStaking($this->player->id);

        OrphanStakedAction::execute($playerStakedAssets, data_get($this->noDataResponse, 'data'));

        $this->assertModelMissing($this->stakedAsset);
    }
}
