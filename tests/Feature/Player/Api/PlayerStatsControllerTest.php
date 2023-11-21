<?php

namespace Tests\Feature\Player\Api;

use Tests\TestCase;
use Mockery\MockInterface;
use Domain\Player\Models\Player;
use Domain\Asset\Models\StakedAsset;
use Illuminate\Auth\Authenticatable;
use Domain\Log\Models\TransactionLog;
use Domain\Shared\ValueObjects\Token;
use Illuminate\Support\Facades\Cache;
use Domain\Atomic\Actions\FilterAssetsAction;
use Illuminate\Testing\Fluent\AssertableJson;
use Domain\Player\ValueObjects\PlayerBalances;
use Domain\Player\ValueObjects\PlayerServices;
use Domain\Asset\Repositories\AssetsRepository;
use Domain\Player\Repositories\BalancesRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Domain\Asset\Actions\GetServiceConsumptionAction;

class PlayerStatsControllerTest extends TestCase
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
    public function it_validates_authentication()
    {
        $this->json('GET', route('api.player.stats'))
            ->assertUnauthorized();
    }

    /** @test */
    public function it_returns_player_stats()
    {
        $this->actingAs($this->player);

        $this->mock(BalancesRepository::class, function (MockInterface $mock) {
            $mock->shouldReceive('getBalances')
                ->andReturn(
                    Cache::put(
                        "players/{$this->player->id}/balances",
                        PlayerBalances::from(
                            Token::from(1.0),
                            Token::from(2.0),
                        ),
                        BalancesRepository::CACHED_TIME,
                    )
                );
        });

        $this->get(route('api.player.stats'))
            ->assertJson(
                fn (AssertableJson $json) => $json
                    ->where('success', true)
                    ->hasAll(['data.hootBalance', 'data.waxBalance', 'data.processing'])
                    ->where('data.hootBalance.value', 1)
                    ->where('data.waxBalance.value', 2)
                    ->where('data.processing', false)
            )
            ->assertOk();
    }

    /** @test */
    public function it_indicates_processing_true_while_transaction_is_processing_on_player_stats()
    {
        $this->actingAs($this->player);

        $this->mock(BalancesRepository::class, function (MockInterface $mock) {
            $mock->shouldReceive('getBalances')
                ->andReturn(
                    Cache::put(
                        "players/{$this->player->id}/balances",
                        PlayerBalances::from(
                            Token::from(1.0),
                            Token::from(2.0),
                        ),
                        BalancesRepository::CACHED_TIME,
                    )
                );
        });

        TransactionLog::factory()->processing()->create([
            'player_id' => $this->player->id,
        ]);

        $this->get(route('api.player.stats'))
            ->assertJson(
                fn (AssertableJson $json) => $json
                    ->where('success', true)
                    ->hasAll(['data.hootBalance', 'data.waxBalance', 'data.processing'])
                    ->where('data.hootBalance.value', 1)
                    ->where('data.waxBalance.value', 2)
                    ->where('data.processing', true)
            )
            ->assertOk();
    }

    /** @test */
    public function it_returns_player_service_consumption()
    {
        $this->actingAs($this->player);

        $stakedAssets = StakedAsset::factory(2)->withData([
            'population' => 5,
            'water' => 5,
            'energy' => 5,
        ])->create([
            'player_id' => $this->player->id,
            'staked_at' => now()->subDay(),
        ]);

        $this->mock(BalancesRepository::class, function (MockInterface $mock) {
            $mock->shouldReceive('getBalances')
                ->andReturn(
                    Cache::put(
                        "players/{$this->player->id}/balances",
                        PlayerBalances::from(
                            Token::from(1.0),
                            Token::from(2.0),
                        ),
                        BalancesRepository::CACHED_TIME,
                    )
                );
        });

        $assets = FilterAssetsAction::execute($this->atomicResponseWithServices);

        $this->mock(
            AssetsRepository::class,
            fn (MockInterface $mock) => $mock
                ->shouldReceive('getPlayerAssets')
                ->andReturn($assets)
        );

        /** @var PlayerServices */
        $consumption = GetServiceConsumptionAction::execute($stakedAssets);

        $this->json('GET', route('api.player.stats'))
            ->assertJson(
                fn (AssertableJson $json) => $json
                    ->where('success', true)
                    ->has('data.consumption')
                    ->where('data.consumption.water.total', $consumption->water->total)
                    ->where('data.consumption.water.current', $consumption->water->current)
                    ->where('data.consumption.energy.total', $consumption->energy->total)
                    ->where('data.consumption.energy.current', $consumption->energy->current)
                    ->where('data.population', 10)
            )
            ->assertOk();
    }
}
