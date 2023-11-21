<?php

namespace Tests\Feature\Atomic;

use Tests\TestCase;
use Mockery\MockInterface;
use Domain\Player\Models\Player;
use Domain\Asset\Models\StakedAsset;
use Illuminate\Auth\Authenticatable;
use Domain\Asset\Enums\AssetSchemaType;
use Domain\Atomic\Actions\GetAssetsAction;
use Domain\Asset\Collections\AssetCollection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Domain\Atomic\Interfaces\AtomicApiManagerInterface;

class GetAssetsActionTest extends TestCase
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
    public function it_gets_player_assets_from_atomic_as_collection()
    {
        $stakedAsset = StakedAsset::factory()
            ->withData([
                'population' => 0,
                'schema' => AssetSchemaType::Residential,
            ])
            ->create([
                'player_id' => $this->player->id,
                'asset_id' => $this->residential->assetId,
            ]);

        $stakedAssets = StakedAsset::where('player_id', $this->player->id)->get();

        $this->mock(AtomicApiManagerInterface::class, function (MockInterface $mock) {
            $mock->shouldReceive('assets')->andReturn($this->atomicResponse);
        });

        /** @var AssetCollection */
        $actualAssets = GetAssetsAction::execute(
            $this->player,
            $stakedAssets
        )->loadOnStaking($stakedAssets);

        $residential = $this->residential;
        $residential->staking = true;
        $residential->position_x = $stakedAsset->position_x;
        $residential->position_y = $stakedAsset->position_y;

        $expected = new AssetCollection([$residential, $this->specialBuild]);

        $this->assertEquals($expected, $actualAssets);
    }

    /** @test */
    public function it_returns_empty_collection_if_user_has_not_assets()
    {
        $this->mock(AtomicApiManagerInterface::class, function (MockInterface $mock) {
            $mock->shouldReceive('assets')->andReturn($this->noDataResponse);
        });

        $stakedAssets = StakedAsset::getPlayerAssetsInStaking($this->player->id);

        $assetsActual = GetAssetsAction::execute($this->player, $stakedAssets);

        $expected = new AssetCollection([]);

        $this->assertEquals($expected, $assetsActual);
    }

    /** @test */
    public function it_throws_an_exception_if_atomic_response_returns_null()
    {
        $this->mock(AtomicApiManagerInterface::class, function (MockInterface $mock) {
            $mock->shouldReceive('assets')->andReturn([]);
        });

        $stakedAssets = StakedAsset::getPlayerAssetsInStaking($this->player->id);

        $this->expectExceptionMessage('There was a problem requesting the assets from Atomic, this was returned: []');

        GetAssetsAction::execute($this->player, $stakedAssets);
    }

    /** @test */
    public function it_throws_an_exception_if_atomic_response_returns_data_with_null()
    {
        $this->mock(AtomicApiManagerInterface::class, function (MockInterface $mock) {
            $mock->shouldReceive('assets')->andReturn(['data' => null]);
        });

        $stakedAssets = StakedAsset::getPlayerAssetsInStaking($this->player->id);

        $this->expectExceptionMessage('There was a problem requesting the assets from Atomic, this was returned: {"data":null}');

        GetAssetsAction::execute($this->player, $stakedAssets);
    }

    /** @test */
    public function it_does_not_throw_an_exception_if_atomic_response_returns_data_with_empty_array()
    {
        $this->mock(AtomicApiManagerInterface::class, function (MockInterface $mock) {
            $mock->shouldReceive('assets')->andReturn(['data' => []]);
        });

        $stakedAssets = StakedAsset::getPlayerAssetsInStaking($this->player->id);

        $collection = GetAssetsAction::execute($this->player, $stakedAssets);

        $this->assertEmpty($collection);
    }
}
