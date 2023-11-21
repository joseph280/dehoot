<?php

namespace Tests\Feature\Asset\Controllers\Api;

use Tests\TestCase;
use Mockery\MockInterface;
use Domain\Player\Models\Player;
use Domain\Asset\Models\StakedAsset;
use Illuminate\Auth\Authenticatable;
use Domain\Asset\Entities\Residential;
use Domain\Shared\Enums\MessageStatus;
use Domain\Asset\Enums\AssetSchemaType;
use Domain\Asset\Collections\AssetCollection;
use Illuminate\Testing\Fluent\AssertableJson;
use Domain\Asset\Repositories\AssetsRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Domain\Asset\Actions\CheckServiceConsumptionAction;
use Domain\Atomic\Interfaces\AtomicApiManagerInterface;

class StakeControllerTest extends TestCase
{
    use RefreshDatabase;

    protected Player $player;

    public function setUp(): void
    {
        parent::setUp();

        /** @var Player|Authenticatable */
        $this->player = Player::factory()->create();

        $this->data = [
            'template_id' => '431295',
            'asset_id' => '1099668471868',
            'land' => '1',
            'position_x' => '1',
            'position_y' => '1',
        ];

        $this->residential = $this->regularResponse->residential;

        $this->assetData = $this->residential;
    }

    /** @test */
    public function unauthenticated_user_cant_see_stake_assets()
    {
        $this->postJson(route('api.assets.stake'), $this->data)
            ->assertUnauthorized();
    }

    /** @test */
    public function it_can_stake_assets()
    {
        $this->actingAs($this->player);

        $assetData = $this->assetData;

        $this->mock(
            AtomicApiManagerInterface::class,
            fn (MockInterface $mock) => $mock->shouldReceive('assets')->andReturn($this->atomicResponse)
        );

        $this->mock(
            CheckServiceConsumptionAction::class,
            fn (MockInterface $mock) => $mock->shouldReceive('handle')->andReturn(true)
        );

        $this->postJson(route('api.assets.stake'), $this->data)
            ->assertCreated()
            ->assertJson(
                fn (AssertableJson $json) => $json
                    ->where('success', true)
                    ->where('data.asset_id', $this->data['asset_id'])
            );

        $this->assertDatabaseHas('staked_assets', [
            'asset_id' => $this->data['asset_id'],
            'data' => $this->castAsJson($assetData->toArray()),
            'player_id' => $this->player->id,
        ]);
    }

    /** @test */
    public function cant_stake_if_user_has_reached_staking_limit()
    {
        $this->actingAs($this->player);

        StakedAsset::factory(10)->create([
            'player_id' => $this->player->id,
            'data->schema' => Residential::SCHEMA_NAME,
        ]);

        $this->mock(
            AssetsRepository::class,
            fn (MockInterface $mock) => $mock->shouldReceive('getFreshPlayerAssets')
                ->andReturn(new AssetCollection([]))
        );

        $this->postJson(route('api.assets.stake'), $this->data)
            ->assertUnprocessable()
            ->assertJson(
                fn (AssertableJson $json) => $json
                    ->hasAll(['success', 'flash'])
                    ->where('flash', [
                        'message' => __('messages.stake.reached_stake_limit'),
                        'status' => MessageStatus::Danger->value,
                    ])
            );

        $jsonData = json_encode([
            'template_id' => $this->assetData->templateId,
            'schema' => $this->assetData->schema,
            'population' => $this->assetData->population,
            'water' => $this->assetData->water,
            'energy' => $this->assetData->energy,
        ]);

        $this->assertDatabaseMissing('staked_assets', [
            'asset_id' => $this->data['asset_id'],
            'data' => $jsonData,
            'player_id' => $this->player->id,
        ]);
    }

    /** @test */
    public function it_cannot_stake_asset_if_user_has_not_enough_service_capacity()
    {
        $this->actingAs($this->player);

        $assetData = $this->assetData;

        $this->mock(AtomicApiManagerInterface::class, function (MockInterface $mock) {
            $mock->shouldReceive('assets')->andReturn($this->atomicResponse);
        });

        $this->mock(CheckServiceConsumptionAction::class, function (MockInterface $mock) {
            $mock->shouldReceive('handle')->andReturn(false);
        });

        $this->post(route('api.assets.stake'), $this->data)
            ->assertUnprocessable()
            ->assertJson(
                fn (AssertableJson $json) => $json
                    ->hasAll(['success', 'flash'])
                    ->where('flash', [
                        'message' => __('messages.stake.reached_consumption_limit'),
                        'status' => MessageStatus::Danger->value,
                    ])
            );

        $this->assertDatabaseMissing('staked_assets', [
            'asset_id' => $this->data['asset_id'],
            'data' => json_encode($assetData->toArray()),
            'player_id' => $this->player->id,
        ]);
    }

    /** @test */
    public function player_cant_stake_assets_that_he_owns_and_already_exist()
    {
        $this->actingAs($this->player);

        /** @var StakedAsset */
        $stakedAsset = StakedAsset::factory()->withData([
            'schema' => AssetSchemaType::Residential,
            'staked_at' => now()->subDay(),
        ])->create([
            'player_id' => $this->player->id,
            'asset_id' => '1234',
        ]);

        $data = [
            'asset_id' => '1234',
            'template_id' => '1234',
            'land' => $stakedAsset->land,
            'position_x' => $stakedAsset->position_x,
            'position_y' => $stakedAsset->position_y,
        ];

        $this->mock(
            AssetsRepository::class,
            fn (MockInterface $mock) => $mock->shouldReceive('getFreshPlayerAssets')
                ->andReturn(new AssetCollection([]))
        );

        $this->post(route('api.assets.stake'), $data)
            ->assertUnprocessable()
            ->assertJson(
                fn (AssertableJson $json) => $json
                    ->hasAll(['success', 'flash'])
                    ->where('flash', [
                        'message' => __('messages.errors.asset_already_in_staking'),
                        'status' => MessageStatus::Danger->value,
                    ])
            );

        $stakedAsset->fresh();
        $this->assertDatabaseCount('staked_assets', 1);
        $this->assertDatabaseHas('staked_assets', [
            'asset_id' => '1234',
            'player_id' => $this->player->id,
        ]);
    }

    /** @test */
    public function player_can_stake_assets_that_already_exist_but_ownership_is_not_updated()
    {
        $this->actingAs($this->player);

        $oldOwner = Player::factory()->create();

        /** @var StakedAsset */
        $stakedAsset = StakedAsset::factory()->withData([
            'schema' => AssetSchemaType::Residential,
        ])->create([
            'player_id' => $oldOwner->id,
            'asset_id' => '1234',
        ]);

        $data = [
            'asset_id' => '1234',
            'template_id' => '1234',
            'land' => $stakedAsset->land,
            'position_x' => $stakedAsset->position_x,
            'position_y' => $stakedAsset->position_y,
        ];

        $playerAssetsCollection = new AssetCollection([
            new Residential(
                assetId: '1234',
                templateId: '1234',
                schema: $stakedAsset->data->schema,
                owner: $this->player->account_id,
                imgUrl: $stakedAsset->data->imgUrl,
                name: $stakedAsset->data->name,
                description: $stakedAsset->data->description,
                type: $stakedAsset->data->type,
                population: $stakedAsset->data->population,
                water: $stakedAsset->data->water,
                energy: $stakedAsset->data->energy,
                level: $stakedAsset->data->level,
                season: $stakedAsset->data->season,
            ),
        ]);

        $this->mock(
            AssetsRepository::class,
            fn (MockInterface $mock) => $mock->shouldReceive('getFreshPlayerAssets')
                ->andReturn($playerAssetsCollection)
        );

        $this->mock(
            CheckServiceConsumptionAction::class,
            fn (MockInterface $mock) => $mock->shouldReceive('handle')->andReturn(true)
        );

        $this->post(route('api.assets.stake'), $data)
            ->assertCreated()
            ->assertJson(
                fn (AssertableJson $json) => $json
                    ->where('success', true)
                    ->where('data.asset_id', '1234')
            );

        $this->assertDatabaseMissing('staked_assets', [
            'id' => $stakedAsset->id,
            'player_id' => $stakedAsset->player_id,
        ]);

        $this->assertDatabaseHas('staked_assets', [
            'asset_id' => '1234',
            'player_id' => $this->player->id,
        ]);
    }

    /** @test */
    public function all_fields_to_stake_an_asset_are_required()
    {
        $this->actingAs($this->player);

        $this->post(route('api.assets.stake'), [])
            ->assertInvalid([
                'asset_id' => trans('validation.required', ['attribute' => 'asset id']),
                'template_id' => trans('validation.required', ['attribute' => 'template id']),
                'land' => trans('validation.required', ['attribute' => 'land']),
                'position_x' => trans('validation.required', ['attribute' => 'position x']),
                'position_y' => trans('validation.required', ['attribute' => 'position y']),
            ]);
    }

    /** @test */
    public function players_cant_stake_assets_offside_limits()
    {
        $this->actingAs($this->player);

        $data = [
            'asset_id' => '1234',
            'template_id' => '1234',
            'land' => '1',
            'position_x' => 0,
            'position_y' => -8,
        ];

        $this->post(route('api.assets.stake'), $data)
            ->assertInvalid([
                'position_x' => trans('validation.between.numeric', ['attribute' => 'position x','min' => '1', 'max' => '12']),
                'position_y' => trans('validation.between.numeric', ['attribute' => 'position y','min' => '1', 'max' => '12']),
            ]);
    }
}
