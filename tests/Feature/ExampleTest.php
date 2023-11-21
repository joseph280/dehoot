<?php

namespace Tests\Feature;

use Tests\TestCase;
use Domain\Player\Models\Player;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_you_can_make_player_factories()
    {
        $player = Player::factory()->create();
        $this->assertNotEmpty($player);
    }
}
