<?php

namespace Tests\Feature\Asset\Controllers\Api;

use Tests\TestCase;
use Mockery\MockInterface;
use Domain\Player\Models\Player;
use Domain\Asset\Models\StakedAsset;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Domain\Atomic\Interfaces\AtomicApiManagerInterface;

class GetPlayerAssetsControllerTest extends TestCase
{
    use RefreshDatabase;

    protected Player $player;

    public function setUp(): void
    {
        parent::setUp();

        $this->player = Player::factory()->create();

        $this->residential = $this->regularResponse->residential;
        $this->specialBuild = $this->regularResponse->specialBuild;
    }

    /** @test */
    public function unauthenticated_player_cant_get_assets()
    {
        $this->json('GET', route('api.player.assets'))
            ->assertUnauthorized();
    }

    /** @test */
    public function it_returns_player_assets()
    {
        $this->actingAs($this->player);

        StakedAsset::factory()->create([
            'player_id' => $this->player->id,
            'asset_id' => $this->residential->assetId,
            'staked_at' => now()->subDay(),
        ]);

        $this->mock(AtomicApiManagerInterface::class, function (MockInterface $mock) {
            $mock->shouldReceive('assets')->andReturn($this->atomicResponse);
        });

        $this->json('GET', route('api.player.assets'))
            ->assertJson(
                fn (AssertableJson $json) => $json
                    ->where('success', true)
                    ->has('data.assets', 2)
                    ->has('data.stakedAssets.residentialBuildings', 1)
                    ->has('data.stakedAssets.residentialBuildings.0.stakedBalance')
                    ->has('data.unstakedAssets.residentialBuildings', 0)
                    ->has('data.unstakedAssets.specialBuildings', 1)
                    ->where('data.stakingLimit', 10)
                    ->where('data.stakedAssets.residentialBuildings.0.assetId', $this->residential->assetId)
                    ->where('data.unstakedAssets.specialBuildings.0.assetId', $this->specialBuild->assetId)
                    ->hasAll(
                        [
                            'metadata.residentialBuildingsCount',
                            'metadata.serviceBuildingsCount',
                            'metadata.specialBuildingsCount',
                        ]
                    )
            )
            ->assertOk();
    }
}
