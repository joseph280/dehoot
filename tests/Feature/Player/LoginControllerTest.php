<?php

namespace Tests\Feature\Player;

use Tests\TestCase;
use Domain\Player\Models\Player;
use Illuminate\Auth\Authenticatable;
use Inertia\Testing\AssertableInertia;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoginControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function get_login_view()
    {
        $this->get('/login')
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->component('Login'));
    }

    /** @test */
    public function login_redirects_to_home_if_player_is_authenticated()
    {
        /** @var Player|Authenticatable */
        $player = Player::factory()->create();
        $this->actingAs($player);

        $response = $this->get('/login');
        $response->assertRedirect('/home');
    }
}
