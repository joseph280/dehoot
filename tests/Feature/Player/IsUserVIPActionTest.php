<?php

namespace Tests\Feature\Player;

use Tests\TestCase;
use Domain\Player\Models\Player;
use Illuminate\Auth\Authenticatable;
use Domain\Player\Actions\IsUserVIPAction;
use Domain\Asset\Collections\AssetCollection;
use Domain\Atomic\Actions\FilterAssetsAction;
use Illuminate\Foundation\Testing\RefreshDatabase;

class IsUserVIPActionTest extends TestCase
{
    use RefreshDatabase;

    protected Player $player;

    public function setUp(): void
    {
        parent::setUp();

        /** @var Player|Authenticatable */
        $this->player = Player::factory()->create();
        $this->actingAs($this->player);
    }

    /** @test */
    public function it_verifies_that_player_is_not_vip()
    {
        $this->assertFalse(
            IsUserVIPAction::execute($this->player, new AssetCollection([]))
        );
    }

    /** @test */
    public function it_verifies_that_player_is_vip()
    {
        $assetsCollection = FilterAssetsAction::execute($this->atomicSpecialBuildResponse);

        $this->assertTrue(
            IsUserVIPAction::execute($this->player, $assetsCollection)
        );
    }
}
