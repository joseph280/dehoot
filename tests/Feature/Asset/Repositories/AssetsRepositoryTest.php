<?php

namespace Tests\Feature\Asset\Repositories;

use Tests\TestCase;
use Mockery\MockInterface;
use Domain\Player\Models\Player;
use Domain\Asset\Models\StakedAsset;
use Illuminate\Support\Facades\Cache;
use Domain\Atomic\Actions\GetAssetsAction;
use Domain\Asset\Repositories\AssetsRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Domain\Atomic\Interfaces\AtomicApiManagerInterface;

class AssetsRepositoryTest extends TestCase
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
    public function it_returns_auth_user_assets()
    {
        StakedAsset::factory()->create([
            'player_id' => $this->player->id,
            'asset_id' => $this->residential->assetId,
            'staked_at' => now()->subDay(),
        ]);

        $this->mock(AtomicApiManagerInterface::class, function (MockInterface $mock) {
            $mock->shouldReceive('assets')->andReturn($this->atomicResponse);
        });

        $cachedAssets = GetAssetsAction::execute($this->player, $this->player->stakedAssets);

        $this->mock(GetAssetsAction::class, function (MockInterface $mock) {
            $mock->shouldReceive('handle')->times(0);
        });

        Cache::shouldReceive('remember')
            ->once()
            ->withSomeOfArgs("players/{$this->player->id}/assets")
            ->andReturn($cachedAssets);

        $assetsRepository = new AssetsRepository();
        $assets = $assetsRepository->getPlayerAssets($this->player);

        $this->assertCount(2, $assets);
    }
}
