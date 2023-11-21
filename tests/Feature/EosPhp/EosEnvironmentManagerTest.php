<?php

namespace Tests\Feature\EosPhp;

use Tests\TestCase;
use Domain\Player\Models\Player;
use Illuminate\Auth\Authenticatable;
use Domain\EosPhp\Enums\EosEnvironmentStatus;
use Domain\EosPhp\Support\EosEnvironmentManager;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EosEnvironmentManagerTest extends TestCase
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
    public function it_returns_rpc_for_testing()
    {
        $eosEnv = new EosEnvironmentManager(EosEnvironmentStatus::Testing);

        $rpc = $eosEnv->getRPC();

        $this->assertEquals('https://testnet.waxsweden.org', $rpc);
    }

    /** @test */
    public function it_returns_rpc_for_production()
    {
        $eosEnv = new EosEnvironmentManager(EosEnvironmentStatus::Production);

        $rpc = $eosEnv->getRPC();

        $this->assertEquals('https://chain.wax.io', $rpc);
    }

    /** @test */
    public function it_returns_main_account_for_testing()
    {
        $eosEnv = new EosEnvironmentManager(EosEnvironmentStatus::Testing);

        $account = $eosEnv->getContractAccount();

        $this->assertEquals('myaccount', $account);
    }

    /** @test */
    public function it_returns_main_account_for_production()
    {
        putenv('WAX_ACCOUNT=myaccount');

        $eosEnv = new EosEnvironmentManager(EosEnvironmentStatus::Production);

        $account = $eosEnv->getContractAccount();

        $this->assertEquals('myaccount', $account);
    }

    /** @test */
    public function it_returns_transfer_account_for_testing()
    {
        $eosEnv = new EosEnvironmentManager(EosEnvironmentStatus::Testing);

        $transferAccount = $eosEnv->getTransferReceiverAccount($this->player);

        $this->assertEquals('mytransferaccount', $transferAccount);
    }

    /** @test */
    public function it_returns_transfer_account_for_production()
    {
        $eosEnv = new EosEnvironmentManager(EosEnvironmentStatus::Production);

        $transferAccount = $eosEnv->getTransferReceiverAccount($this->player);

        $this->assertEquals($transferAccount, $this->player->account_id);
    }
}
