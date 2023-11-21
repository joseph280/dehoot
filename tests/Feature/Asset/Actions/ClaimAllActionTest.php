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
use Domain\Asset\Effects\TheHutEffect;
use Domain\Shared\ValueObjects\Reward;
use Domain\Asset\Enums\AssetSchemaType;
use Domain\Log\Enums\TransactionStatus;
use Domain\Log\ValueObjects\ActionData;
use Domain\Asset\Actions\ClaimAllAction;
use Domain\Asset\Actions\TransactAction;
use Domain\Asset\Actions\GetRewardAction;
use Domain\Asset\Actions\CheckRewardAction;
use Domain\Log\Enums\TransactionActionName;
use Domain\EosPhp\Support\EosEnvironmentManager;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ClaimAllActionTest extends TestCase
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
    public function claim_all_is_successful()
    {
        $stakedAssets = StakedAsset::factory(3)->create([
            'player_id' => $this->player->id,
            'staked_at' => now()->subDay(),
        ]);

        $this->mock(
            GetRewardAction::class,
            fn (MockInterface $mock) => $mock
                ->shouldReceive('handle')
                ->andReturn(new Reward(
                    staked: Token::from(1.0),
                    bonus: Token::from(0.0),
                    tax: Token::from(0.05),
                    total: Token::from(0.9500),
                    stakedAssets: $stakedAssets
                ))
        );

        /** @var EosEnvironmentManager */
        $eosEnv = app(EosEnvironmentManager::class);
        $actionName = TransactionActionName::Transfer->value;

        $actionData = new ActionData(
            from: $eosEnv->getContractAccount(),
            to: $eosEnv->getTransferReceiverAccount($this->player),
            quantity: '0.9500 HOOT',
            memo: $actionName,
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
                        'asset_ids' => $stakedAssets->pluck('asset_id'),
                        'type' => TransactionType::ClaimAll,
                    ])
                )
        );

        ClaimAllAction::execute($stakedAssets, $this->player);

        $stakedAssets->fresh()->each(function ($stakedAsset) {
            $this->assertTrue($stakedAsset->staked_at->isSameHour(now()));
        });
    }

    /** @test */
    public function claim_all_has_not_reward_and_staked_asset_staked_at_are_not_updated_and_transaction_log_is_deleted()
    {
        $stakedAssets = StakedAsset::factory(2)->create([
            'player_id' => $this->player->id,
            'staked_at' => now(),
            'updated_at' => now()->subDay(),
        ]);

        TransactionLog::factory()->create([
            'player_id' => $this->player->id,
            'asset_ids' => $stakedAssets->pluck('asset_id'),
            'type' => TransactionType::ClaimAll,
            'status' => TransactionStatus::Processing,
        ]);

        $this->mock(
            GetRewardAction::class,
            fn (MockInterface $mock) => $mock
                ->shouldReceive('handle')
                ->andReturn(new Reward(
                    staked: Token::from(0.0),
                    bonus: Token::from(0.0),
                    tax: Token::from(0.0),
                    total: Token::from(0.0),
                    stakedAssets: $stakedAssets
                ))
        );

        ClaimAllAction::execute($stakedAssets, $this->player);

        $this->assertDatabaseCount('transaction_logs', 0);

        $stakedAssets->fresh()->each(function ($stakedAsset) {
            $this->assertTrue($stakedAsset->updated_at->isSameDay(now()->subDay()));
        });
    }

    /** @test */
    public function claim_all_transact_is_not_successful_and_staked_at_is_not_updated()
    {
        $stakedAssets = StakedAsset::factory(2)->create([
            'player_id' => $this->player->id,
            'staked_at' => now(),
            'updated_at' => now()->subDay(),
        ]);

        $this->mock(
            GetRewardAction::class,
            fn (MockInterface $mock) => $mock
                ->shouldReceive('handle')
                ->andReturn(new Reward(
                    staked: Token::from(0.0),
                    bonus: Token::from(0.0),
                    tax: Token::from(0.0),
                    total: Token::from(0.0),
                    stakedAssets: $stakedAssets
                ))
        );

        /** @var EosEnvironmentManager */
        $eosEnv = app(EosEnvironmentManager::class);
        $actionName = TransactionActionName::Transfer->value;

        $actionData = new ActionData(
            from: $eosEnv->getContractAccount(),
            to: $eosEnv->getTransferReceiverAccount($this->player),
            quantity: '0.0000 HOOT',
            memo: $actionName,
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
                        'status' => TransactionStatus::Failed,
                        'asset_ids' => $stakedAssets->pluck('asset_id'),
                        'type' => TransactionType::ClaimAll,
                    ])
                )
        );

        ClaimAllAction::execute($stakedAssets, $this->player);

        $stakedAssets->fresh()->each(function ($stakedAsset) {
            $this->assertTrue($stakedAsset->updated_at->isSameDay(now()->subDay()));
        });
    }

    /** @test */
    public function residential_and_specials_staked_at_are_reset_after_successful_claim()
    {
        StakedAsset::factory()
            ->withData([
                'template_id' => '1',
                'schema' => AssetSchemaType::Residential,
                'population' => 1,
            ])
            ->create([
                'player_id' => $this->player->id,
                'asset_id' => '1',
                'staked_at' => now()->subDay(),
            ]);

        StakedAsset::factory()
            ->withData([
                'template_id' => TheHutEffect::TEMPLATE_ID,
                'schema' => AssetSchemaType::Special,
                'population' => 0,
            ])
            ->create([
                'player_id' => $this->player->id,
                'asset_id' => '2',
                'staked_at' => now()->subDay(),
            ]);

        $stakedAssets = $this->player->stakedAssets;

        $this->mock(
            GetRewardAction::class,
            fn (MockInterface $mock) => $mock
                ->shouldReceive('handle')
                ->andReturn(
                    new Reward(
                        staked: Token::from(1.0),
                        bonus: Token::from(0.5),
                        tax: Token::from(17.575),
                        total: Token::from(333.925),
                        stakedAssets: $stakedAssets
                    )
                )
        );

        $this->mock(CheckRewardAction::class, function (MockInterface $mock) {
            $mock->shouldReceive('handle')->andReturn(true);
        });

        /** @var EosEnvironmentManager */
        $eosEnv = app(EosEnvironmentManager::class);
        $actionName = TransactionActionName::Transfer->value;

        $actionData = new ActionData(
            from: $eosEnv->getContractAccount(),
            to: $eosEnv->getTransferReceiverAccount($this->player),
            quantity: '333.9250 HOOT',
            memo: $actionName,
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
                        'asset_ids' => $stakedAssets->pluck('asset_id'),
                        'type' => TransactionType::ClaimAll,
                    ])
                )
        );

        ClaimAllAction::execute($stakedAssets, $this->player);

        $stakedAssets->fresh()->each(function ($stakedAsset) {
            $this->assertTrue($stakedAsset->staked_at->isSameDay(now()));
        });
    }
}
