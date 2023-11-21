<?php

namespace Tests\Feature\EosPhp;

use Tests\TestCase;
use Mockery\MockInterface;
use Domain\Player\Models\Player;
use Illuminate\Auth\Authenticatable;
use Domain\EosPhp\Actions\GetBalanceAction;
use Domain\EosPhp\Interfaces\EosApiInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetBalanceActionTest extends TestCase
{
    use RefreshDatabase;

    public Player $player;

    public function setUp(): void
    {
        parent::setUp();

        /** @var Player|Authenticatable */
        $this->player = Player::factory()->create();
        $this->actingAs($this->player);
    }

    /** @test */
    public function it_returns_player_balance_specifying_contract_and_token()
    {
        $this->mock(EosApiInterface::class, function (MockInterface $mock) {
            $mock->shouldReceive('getBalance')->andReturn(['216.63005896 WAX']);
        });

        $balance = GetBalanceAction::execute(
            'eosio.token',
            'waxronaldtes',
            'WAX',
        );

        $this->assertEquals('216.6301 WAX', $balance->formattedWithToken);
    }

    /** @test */
    public function it_returns_player_balance_as_zero_if_balance_array_empty()
    {
        $this->mock(EosApiInterface::class, function (MockInterface $mock) {
            $mock->shouldReceive('getBalance')->andReturn([]);
        });

        $balance = GetBalanceAction::execute(
            'eosio.token',
            'waxronaldtes',
            'HOOT',
        );

        $this->assertEquals('0.0000 HOOT', $balance->formattedWithToken);
    }

    /** @test */
    public function it_returns_player_balance_as_zero_if_balance_is_null()
    {
        $this->mock(EosApiInterface::class, function (MockInterface $mock) {
            $mock->shouldReceive('getBalance')->andReturn([null]);
        });

        $balance = GetBalanceAction::execute(
            'eosio.token',
            'waxronaldtes',
            'HOOT',
        );

        $this->assertEquals('0.0000 HOOT', $balance->formattedWithToken);
    }
}
