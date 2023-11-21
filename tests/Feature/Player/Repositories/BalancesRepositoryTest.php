<?php

namespace Tests\Feature\Player\Repositories;

use Closure;
use Tests\TestCase;
use Mockery\MockInterface;
use Domain\Player\Models\Player;
use Illuminate\Auth\Authenticatable;
use Domain\Shared\ValueObjects\Token;
use Illuminate\Support\Facades\Cache;
use Domain\Player\Actions\GetWaxBalanceAction;
use Domain\Player\ValueObjects\PlayerBalances;
use Domain\Player\Actions\GetHootBalanceAction;
use Domain\Player\Repositories\BalancesRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BalancesRepositoryTest extends TestCase
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
    public function it_cache_and_returns_wax_and_hoot_balances()
    {
        Cache::spy();

        $balanceRepository = new BalancesRepository();

        $this->mock(GetHootBalanceAction::class, function (MockInterface $mock) {
            $mock->shouldReceive('handle')->andReturn(Token::from(1200.0));
        });

        $this->mock(GetWaxBalanceAction::class, function (MockInterface $mock) {
            $mock->shouldReceive('handle')->andReturn(Token::from(400.0));
        });

        $expected = PlayerBalances::from(
            Token::from(1200.0),
            Token::from(400.0),
        );

        Cache::shouldReceive('remember')
            ->once()
            ->with(
                "players/{$this->player->id}/balances",
                BalancesRepository::CACHED_TIME,
                Closure::class
            )
            ->andReturn($expected);

        $actual = $balanceRepository->getBalances($this->player);

        $this->assertEquals($expected, $actual);
    }
}
