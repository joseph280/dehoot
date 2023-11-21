<?php

namespace Tests\Feature\Asset\Controllers\Api;

use Tests\TestCase;
use Domain\Player\Models\Player;
use Domain\Asset\Entities\Service;
use App\Jobs\UnstakeTransactionJob;
use Illuminate\Support\Facades\Bus;
use Domain\Asset\Models\StakedAsset;
use Domain\Log\Models\TransactionLog;
use Illuminate\Support\Facades\Queue;
use Domain\Shared\Enums\MessageStatus;
use Domain\Asset\Enums\AssetSchemaType;
use Domain\Asset\Effects\MajorPlazaEffect;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UnstakeControllerTest extends TestCase
{
    use RefreshDatabase;

    protected Player $player;

    public function setUp(): void
    {
        parent::setUp();

        $this->player = Player::factory()->create();
    }

    /** @test */
    public function unauthorized_user_cant_unstake_asset()
    {
        $this->postJson(route('api.assets.unstake'), [])
            ->assertUnauthorized();
    }

    /** @test */
    public function it_dispatches_unstake_transaction_job_when_unstaking()
    {
        Queue::fake();

        $this->actingAs($this->player);

        $stakedAsset = StakedAsset::factory()->create([
            'player_id' => $this->player->id,
            'staked_at' => now()->subDay(),
        ]);

        $this->postJson(route('api.assets.unstake'), ['asset_id' => $stakedAsset->asset_id])
            ->assertOk();

        Queue::assertPushed(
            UnstakeTransactionJob::class,
            function (UnstakeTransactionJob $job) use ($stakedAsset) {
                return $job->assetId === $stakedAsset->asset_id;
            }
        );
    }

    /** @test */
    public function cant_unstake_if_another_transaction_is_being_processed()
    {
        Queue::fake();

        $this->actingAs($this->player);

        $stakedAsset = StakedAsset::factory()->create([
            'player_id' => $this->player->id,
            'staked_at' => now(),
        ]);

        TransactionLog::factory()
            ->processing()
            ->for($this->player)
            ->create();

        $this->postJson(route('api.assets.unstake'), ['asset_id' => $stakedAsset->asset_id])
            ->assertUnprocessable()
            ->assertJson(
                fn (AssertableJson $json) => $json
                    ->where('success', false)
                    ->has('flash')
                    ->where('flash.status', MessageStatus::Danger->value)
                    ->where('flash.message', __('messages.transaction.already_processing'))
            );

        Queue::assertNothingPushed();
    }

    /** @test */
    public function cant_unstake_major_plaza_if_residential_assets_are_not_under_the_staking_limit()
    {
        Queue::fake();

        $this->actingAs($this->player);

        StakedAsset::factory(14)->create([
            'player_id' => $this->player->id,
            'staked_at' => now(),
        ]);

        $majorPlaza = StakedAsset::factory()
            ->withData(['schema' => AssetSchemaType::Special, 'templateId' => MajorPlazaEffect::TEMPLATE_ID])
            ->create([
                'player_id' => $this->player->id,
                'staked_at' => now(),
            ]);

        $this->postJson(route('api.assets.unstake'), ['asset_id' => $majorPlaza->asset_id])
            ->assertUnprocessable()
            ->assertJson(
                fn (AssertableJson $json) => $json
                    ->where('success', false)
                    ->has('flash')
                    ->where('flash.status', MessageStatus::Danger->value)
                    ->where('flash.message', __('messages.unstake.limit.major_plaza'))
            );

        Queue::assertNothingPushed();
    }

    /** @test */
    public function can_unstake_major_plaza()
    {
        Queue::fake();

        $this->actingAs($this->player);

        $majorPlaza = StakedAsset::factory()
            ->withData(['templateId' => MajorPlazaEffect::TEMPLATE_ID])
            ->create([
                'player_id' => $this->player->id,
                'asset_id' => '3',
                'staked_at' => now()->subDay()->subHour(),
            ]);

        $this->postJson(route('api.assets.unstake'), ['asset_id' => $majorPlaza->asset_id])
            ->assertOk()
            ->assertJson(
                fn (AssertableJson $json) => $json
                    ->where('success', true)
                    ->has('flash')
                    ->where('flash.status', MessageStatus::Information->value)
                    ->where('flash.message', __('messages.transaction.processing'))
            );

        Queue::assertPushed(UnstakeTransactionJob::class);
    }

    /** @test */
    public function cant_unstake_service_if_capacity_is_needed()
    {
        $this->actingAs($this->player);

        $energyServiceStakedAsset = StakedAsset::factory()
            ->withData([
                'schema' => AssetSchemaType::Service,
                'type' => Service::ENERGY_TYPE,
                'capacity' => '200',
            ])
            ->create([
                'player_id' => $this->player->id,
                'staked_at' => now()->subDay(),
            ]);

        $waterServiceStakedAsset = StakedAsset::factory()
            ->withData([
                'schema' => AssetSchemaType::Service,
                'type' => Service::WATER_TYPE,
                'capacity' => '200',
            ])
            ->create([
                'player_id' => $this->player->id,
                'staked_at' => now()->subDay(),
            ]);

        StakedAsset::factory()
            ->withData([
                'schema' => AssetSchemaType::Residential,
                'water' => '250',
                'energy' => '250',
            ])
            ->create([
                'player_id' => $this->player->id,
                'staked_at' => now()->subDay(),
            ]);

        $this->postJson(route('api.assets.unstake'), ['asset_id' => $energyServiceStakedAsset->asset_id])
            ->assertUnprocessable()
            ->assertJson(
                fn (AssertableJson $json) => $json
                    ->where('success', false)
                    ->has('flash')
                    ->where('flash.status', MessageStatus::Danger->value)
                    ->where('flash.message', __('messages.unstake.cant_be_under_consumption_limit'))
            );

        $this->postJson(route('api.assets.unstake'), ['asset_id' => $waterServiceStakedAsset->asset_id])
            ->assertUnprocessable()
            ->assertJson(
                fn (AssertableJson $json) => $json
                    ->where('success', false)
                    ->has('flash')
                    ->where('flash.status', MessageStatus::Danger->value)
                    ->where('flash.message', __('messages.unstake.cant_be_under_consumption_limit'))
            );
    }

    /** @test */
    public function can_unstake_services_if_current_consumption_is_zero()
    {
        $this->actingAs($this->player);

        $energyServiceStakedAsset = StakedAsset::factory()
            ->withData([
                'schema' => AssetSchemaType::Service,
                'type' => Service::ENERGY_TYPE,
                'capacity' => '200',
            ])
            ->create([
                'player_id' => $this->player->id,
                'staked_at' => now()->subDay(),
            ]);

        $this->postJson(route('api.assets.unstake'), ['asset_id' => $energyServiceStakedAsset->asset_id])
            ->assertOk()
            ->assertJson(
                fn (AssertableJson $json) => $json
                    ->where('success', true)
                    ->has('flash')
                    ->where('flash.status', MessageStatus::Information->value)
                    ->where('flash.message', __('messages.transaction.processing'))
            );

        $this->assertDatabaseMissing('staked_assets', [
            'asset_id' => $energyServiceStakedAsset->asset_id,
        ]);
    }

    /** @test */
    public function job_is_not_dispatched_when_player_unstake_a_service()
    {
        Bus::fake();

        $this->actingAs($this->player);

        $energyServiceStakedAsset = StakedAsset::factory()
            ->withData([
                'schema' => AssetSchemaType::Service,
                'type' => Service::ENERGY_TYPE,
                'capacity' => '200',
            ])
            ->create([
                'player_id' => $this->player->id,
                'staked_at' => now()->subDay(),
            ]);

        $this->postJson(route('api.assets.unstake'), ['asset_id' => $energyServiceStakedAsset->asset_id])
            ->assertOk();

        Bus::assertNotDispatched(UnstakeTransactionJob::class);
    }

    /** @test */
    public function transaction_log_is_not_created_when_player_unstake_a_service()
    {
        $this->actingAs($this->player);

        $energyServiceStakedAsset = StakedAsset::factory()
            ->withData([
                'schema' => AssetSchemaType::Service,
                'type' => Service::ENERGY_TYPE,
                'capacity' => '200',
            ])
            ->create([
                'player_id' => $this->player->id,
                'staked_at' => now()->subDay(),
            ]);

        $this->postJson(route('api.assets.unstake'), ['asset_id' => $energyServiceStakedAsset->asset_id])
            ->assertOk();

        $this->assertDatabaseCount('transaction_logs', 0);
    }

    /** @test */
    public function cant_unstake_an_asset_that_doesnt_exist_in_staked_assets()
    {
        Queue::fake();

        $this->actingAs($this->player);

        $this->postJson(route('api.assets.unstake'), ['asset_id' => '1234'])
            ->assertUnprocessable()
            ->assertJson(
                fn (AssertableJson $json) => $json
                    ->hasAll(['success', 'flash'])
                    ->where('flash', [
                        'message' => __('messages.errors.asset_doesnt_exist'),
                        'status' => MessageStatus::Danger->value,
                    ])
            );

        Queue::assertNotPushed(UnstakeTransactionJob::class);
    }
}
