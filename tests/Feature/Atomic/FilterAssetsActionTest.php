<?php

namespace Tests\Feature\Atomic;

use Tests\TestCase;
use Domain\Player\Models\Player;
use Domain\Asset\Models\StakedAsset;
use Illuminate\Auth\Authenticatable;
use Domain\Asset\Collections\AssetCollection;
use Domain\Atomic\Actions\FilterAssetsAction;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FilterAssetsActionTest extends TestCase
{
    use RefreshDatabase;

    protected Player $player;

    public function setUp(): void
    {
        parent::setUp();

        /** @var Player|Authenticatable */
        $this->player = Player::factory()->create();
        $this->actingAs($this->player);

        $this->residential = $this->regularResponse->residential;
        $this->specialBuild = $this->regularResponse->specialBuild;
    }

    /** @test */
    public function it_returns_assets_by_type()
    {
        $stakedAssets = StakedAsset::getPlayerAssetsInStaking($this->player->id);
        $filteredAssets = FilterAssetsAction::execute($this->atomicResponse, $stakedAssets, $this->player);
        $this->assertEquals(new AssetCollection([$this->residential, $this->specialBuild]), $filteredAssets);
    }
}
