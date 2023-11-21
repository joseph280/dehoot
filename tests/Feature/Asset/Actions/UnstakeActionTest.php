<?php

namespace Tests\Feature\Asset\Actions;

use Tests\TestCase;
use Mockery\MockInterface;
use Domain\Player\Models\Player;
use Domain\Asset\Models\StakedAsset;
use Illuminate\Auth\Authenticatable;
use Domain\Log\Enums\TransactionType;
use Domain\Log\Models\TransactionLog;
use Domain\Shared\ValueObjects\Token;
use Domain\Shared\ValueObjects\Reward;
use Domain\Asset\Actions\UnstakeAction;
use Domain\Log\Enums\TransactionStatus;
use Domain\Log\ValueObjects\ActionData;
use Domain\Asset\Actions\TransactAction;
use Domain\Asset\Actions\GetRewardAction;
use Domain\Asset\Actions\CheckRewardAction;
use Domain\Log\Enums\TransactionActionName;
use Domain\EosPhp\Support\EosEnvironmentManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Domain\Asset\Collections\StakedAssetCollection;

class UnstakeActionTest extends TestCase
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
    public function unstake_is_successful()
    {
        $stakedAsset = StakedAsset::factory()->create();

        $this->mock(
            GetRewardAction::class,
            fn (MockInterface $mock) => $mock
                ->shouldReceive('handle')
                ->andReturn(
                    new Reward(
                        staked: Token::from(1.0),
                        bonus: Token::from(0.0),
                        tax: Token::from(0.05),
                        total: Token::from(0.9500),
                        stakedAssets: new StakedAssetCollection($stakedAsset)
                    )
                )
        );

        $this->mock(
            CheckRewardAction::class,
            fn (MockInterface $mock) => $mock->shouldReceive('handle')->andReturn(true)
        );

        /** @var EosEnvironmentManager */
        $eosEnv = app(EosEnvironmentManager::class);
        $actionName = TransactionActionName::Transfer;

        $actionData = new ActionData(
            from: $eosEnv->getContractAccount(),
            to: $eosEnv->getTransferReceiverAccount($this->player),
            quantity: '0.9500 HOOT',
            memo: $actionName->value,
        );

        $this->mock(
            TransactAction::class,
            fn (MockInterface $mock) => $mock
                ->shouldReceive('handle')
                ->andReturn(
                    TransactionLog::create([
                        'player_id' => $this->player->id,
                        'wallet_id' => $this->player->account_id,
                        'transaction_id' => 'd556e1abbe108e72d3ae2d1b0e1c9e581b95fa21931dee80e77175fd14322ffb',
                        'action_name' => $actionName,
                        'action_data' => $actionData,
                        'status' => TransactionStatus::Success,
                        'asset_ids' => [$stakedAsset->id],
                        'type' => TransactionType::Unstake,
                    ])
                )
        );

        UnstakeAction::execute($stakedAsset, $this->player);

        $this->assertDatabaseMissing('staked_assets', [
            'id' => $stakedAsset->id,
        ]);
    }

    /** @test */
    public function unstake_throw_exception_when_null_staked_asset_is_given()
    {
        $stakedAsset = null;

        $this->mock(
            GetRewardAction::class,
            fn (MockInterface $mock) => $mock
                ->shouldReceive('handle')
                ->andReturn(
                    new Reward(
                        staked: Token::from(0.0),
                        bonus: Token::from(0.0),
                        tax: Token::from(0.0),
                        total: Token::from(0.0),
                        stakedAssets: new StakedAssetCollection([])
                    )
                )
        );

        $this->mock(CheckRewardAction::class, function (MockInterface $mock) {
            $mock->shouldReceive('handle')->andReturn(false);
        });

        $this->expectException('TypeError');

        UnstakeAction::execute($stakedAsset, $this->player);
    }

    /** @test */
    public function unstake_action_deletes_staked_asset_even_if_it_doesnt_have_reward()
    {
        $stakedAsset = StakedAsset::factory()->create([
            'player_id' => $this->player->id,
            'staked_at' => now(),
        ]);

        $this->mock(GetRewardAction::class, function (MockInterface $mock) use ($stakedAsset) {
            $mock->shouldReceive('handle')->andReturn(
                new Reward(
                    staked: Token::from(0.0),
                    bonus: Token::from(0.0),
                    tax: Token::from(0.0),
                    total: Token::from(0.0),
                    stakedAssets: new StakedAssetCollection([]),
                )
            );
        });

        $this->mock(CheckRewardAction::class, function (MockInterface $mock) {
            $mock->shouldReceive('handle')->andReturn(false);
        });

        UnstakeAction::execute($stakedAsset, $this->player);

        $this->assertDatabaseMissing('staked_assets', [
            'id' => $stakedAsset->id,
        ]);
    }

    /** @test */
    public function unstake_action_deletes_transaction_log_if_staked_asset_has_no_reward()
    {
        $stakedAsset = StakedAsset::factory()->create([
            'player_id' => $this->player->id,
            'staked_at' => now(),
        ]);

        $transactionLog = TransactionLog::factory()->processing()->create([
            'player_id' => $this->player->id,
        ]);

        $this->mock(
            GetRewardAction::class,
            fn (MockInterface $mock) => $mock
                ->shouldReceive('handle')
                ->andReturn(
                    new Reward(
                        staked: Token::from(0.0),
                        bonus: Token::from(0.0),
                        tax: Token::from(0.0),
                        total: Token::from(0.0),
                        stakedAssets: new StakedAssetCollection([$stakedAsset]),
                    )
                )
        );

        $this->mock(
            CheckRewardAction::class,
            fn (MockInterface $mock) => $mock
                ->shouldReceive('handle')
                ->andReturn(false)
        );

        UnstakeAction::execute($stakedAsset, $this->player);

        $this->assertDatabaseMissing('transaction_logs', [
            'id' => $transactionLog->id,
        ]);
    }
}
