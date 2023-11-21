<?php

namespace Tests\Feature\Player;

use Tests\TestCase;
use Mockery\MockInterface;
use Domain\Player\Models\Player;
use Illuminate\Auth\Authenticatable;
use Inertia\Testing\AssertableInertia as Assert;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Domain\Atomic\Interfaces\AtomicApiManagerInterface;

class HomeControllerTest extends TestCase
{
    use RefreshDatabase;

    protected Player $player;

    public function setUp(): void
    {
        parent::setUp();

        /** @var Player|Authenticatable */
        $this->player = Player::factory()->create();
    }

    /** @test */
    public function it_verifies_user_auth_in_home_view()
    {
        $this->get(route('home'))->assertStatus(302)->assertRedirect(route('login'));
    }

    /** @test */
    public function it_can_render_home_component()
    {
        $this->actingAs($this->player);

        $this->mock(AtomicApiManagerInterface::class, function (MockInterface $mock) {
            $mock->shouldReceive('assets')->andReturn($this->atomicResponse);
        });

        $this->get(route('home'))
            ->assertStatus(200)
            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('Home')
            );
    }
}
