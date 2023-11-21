<?php

namespace Tests\Feature\Player;

use Tests\TestCase;
use Domain\Player\Models\Player;
use Illuminate\Auth\Authenticatable;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_a_new_player_if_it_does_not_exist()
    {
        $this->post(route('signin'), [
            'account_id' => 'waxtae123456',
            'key_1' => 'test_key_1',
            'key_2' => 'test_key_2',
        ]);

        $newPlayer = Player::query()
            ->where('account_id', 'waxtae123456')
            ->where('key_1', 'test_key_1')
            ->where('key_2', 'test_key_2')
            ->first();

        $this->assertNotNull($newPlayer);
    }

    public function test_it_redirects_to_home_page_after_signing_in()
    {
        $player = Player::factory()->create();

        $this->post(route('signin'), [
            'account_id' => $player->account_id,
            'key_1' => $player->key_1,
            'key_2' => $player->key_2,
        ])
            ->assertRedirect(route('home'));

        $this->assertDatabaseCount('players', 1);
    }

    public function test_it_logouts_a_player()
    {
        /** @var Player|Authenticatable */
        $player = Player::factory()->create();
        $this->actingAs($player);

        $this->post(route('logout'))->assertRedirect(route('login'));
    }
}
