<?php

namespace Tests\Feature\Player\Repositories;

use Tests\TestCase;
use Mockery\MockInterface;
use Domain\Player\Models\Player;
use Illuminate\Support\Facades\Cache;
use Domain\Atomic\Actions\FilterAssetsAction;
use Domain\Asset\Repositories\AssetsRepository;
use Domain\Player\Repositories\PlayerRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PlayerRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected Player $player;

    public function setUp(): void
    {
        parent::setUp();

        $this->player = Player::factory()->create();
    }

    /** @test */
    public function it_stores_player_vip_status()
    {
        $assets = FilterAssetsAction::execute($this->atomicSpecialBuildResponse);

        $this->mock(AssetsRepository::class, function (MockInterface $mock) use ($assets) {
            $mock->shouldReceive('getPlayerAssets')->andReturn($assets);
        });

        Cache::shouldReceive('remember')
            ->once()
            ->withSomeOfArgs("players/{$this->player->id}/is_vip")
            ->andReturn(true);

        $isVip = (new PlayerRepository())->getIsVIP($this->player);

        $this->assertTrue($isVip);
    }
}
