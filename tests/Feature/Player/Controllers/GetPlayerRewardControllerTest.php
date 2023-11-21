<?php

namespace Tests\Feature\Player\Controllers;

use Tests\TestCase;
use Mockery\MockInterface;
use Domain\Player\Models\Player;
use Domain\Asset\Models\StakedAsset;
use Domain\Shared\ValueObjects\Token;
use Domain\Shared\ValueObjects\Reward;
use Domain\Asset\Actions\GetRewardAction;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Domain\Asset\Collections\StakedAssetCollection;

class GetPlayerRewardControllerTest extends TestCase
{
    use RefreshDatabase;

    protected Player $player;

    public function setUp(): void
    {
        parent::setUp();

        $this->player = Player::factory()->create();
    }

    /** @test */
    public function unauthenticated_player_cant_get_reward()
    {
        $this->json('GET', route('api.player.reward'))
            ->assertUnauthorized();
    }

    /** @test */
    public function it_returns_player_reward()
    {
        $this->actingAs($this->player);

        $this->player->is_vip = false;

        $stakedAsset = StakedAsset::factory()->create([
            'player_id' => $this->player->id,
            'staked_at' => now()->subDay(),
        ]);

        $reward = new Reward(
            staked: new Token(1.0),
            bonus: Token::from(0.0),
            tax: Token::from(0.05),
            total: Token::from(0.95),
            stakedAssets: new StakedAssetCollection([$stakedAsset]),
            effects: collect([])
        );

        $this->mock(
            GetRewardAction::class,
            fn (MockInterface $mock) => $mock
                ->shouldReceive('handle')
                ->andReturn($reward)
        );

        $this->json('GET', route('api.player.reward'))
            ->assertJson(
                fn (AssertableJson $json) => $json
                    ->where('success', true)
                    ->has('data.reward')
                    ->where('data.reward.staked.formatted', $reward->staked->formatted)
                    ->where('data.reward.tax.formatted', $reward->tax->formatted)
                    ->where('data.reward.bonus.formatted', $reward->bonus->formatted)
                    ->where('data.reward.total.formatted', $reward->total->formatted)
            )
            ->assertOk();
    }
}
