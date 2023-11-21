<?php

namespace Tests\Feature\Asset;

use Tests\TestCase;
use Domain\Player\Models\Player;
use Domain\Asset\Entities\Service;
use Domain\Asset\Models\StakedAsset;
use Illuminate\Auth\Authenticatable;
use Domain\Asset\Entities\Residential;
use Domain\Asset\Enums\AssetSchemaType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Domain\Asset\Actions\CheckServiceConsumptionAction;

class CheckServiceConsumptionActionTest extends TestCase
{
    use RefreshDatabase;

    protected Player $player;

    public function setUp(): void
    {
        parent::setUp();

        /** @var Player|Authenticatable */
        $this->player = Player::factory()->create();

        StakedAsset::factory()
            ->withData([
                'capacity' => '100',
                'type' => Service::WATER_TYPE,
                'schema' => AssetSchemaType::Service,
            ])
            ->create([
                'player_id' => $this->player->id,
            ]);

        StakedAsset::factory()
            ->withData([
                'capacity' => '100',
                'type' => Service::ENERGY_TYPE,
                'schema' => AssetSchemaType::Service,
            ])
            ->create([
                'player_id' => $this->player->id,
            ]);
    }

    /** @test */
    public function it_returns_true_if_service_consumption_is_valid()
    {
        StakedAsset::factory(2)
            ->withData([
                'population' => 1,
                'water' => 2,
                'energy' => 1,
                'schema' => AssetSchemaType::Residential,
            ])
            ->create([
                'player_id' => $this->player->id,
            ]);

        $stakedAssets = $this->player->stakedAssets;

        $asset = new Residential(
            assetId: '123',
            templateId: '431295',
            schema: AssetSchemaType::Residential->value,
            owner: $this->player->account_id,
            population: 1,
            water: 1,
            energy: 1,
        );

        $actualValue = CheckServiceConsumptionAction::execute($stakedAssets, $asset);

        $this->assertTrue($actualValue);
    }

    /** @test */
    public function it_returns_false_if_service_consumption_is_not_valid()
    {
        StakedAsset::factory(2)
            ->withData([
                'population' => 1,
                'water' => 2,
                'energy' => 1,
                'schema' => AssetSchemaType::Residential,
            ])
            ->create([
                'player_id' => $this->player->id,
            ]);

        $stakedAssets = $this->player->stakedAssets;

        $asset = new Residential(
            assetId: '123',
            templateId: '431295',
            schema: AssetSchemaType::Residential->value,
            owner: $this->player->account_id,
            population: 1,
            water: 200,
            energy: 200,
        );

        $actualValue = CheckServiceConsumptionAction::execute($stakedAssets, $asset);

        $this->assertFalse($actualValue);
    }

    /** @test */
    public function it_returns_false_if_water_is_valid_but_energy_is_not_valid()
    {
        StakedAsset::factory(2)
            ->withData([
                'population' => 1,
                'water' => 2,
                'energy' => 1,
                'schema' => AssetSchemaType::Residential,
            ])
            ->create([
                'player_id' => $this->player->id,
            ]);

        $stakedAssets = $this->player->stakedAssets;

        $asset = new Residential(
            assetId: '123',
            templateId: '431295',
            schema: AssetSchemaType::Residential->value,
            owner: $this->player->account_id,
            population: 1,
            water: 30,
            energy: 2000,
        );

        $actualValue = CheckServiceConsumptionAction::execute($stakedAssets, $asset);

        $this->assertFalse($actualValue);
    }

    /** @test */
    public function it_returns_false_if_water_is_not_valid_but_energy_is_valid()
    {
        StakedAsset::factory(2)
            ->withData([
                'population' => 1,
                'water' => 2,
                'energy' => 1,
                'schema' => AssetSchemaType::Residential,
            ])
            ->create([
                'player_id' => $this->player->id,
            ]);

        $stakedAssets = $this->player->stakedAssets;

        $asset = new Residential(
            assetId: '123',
            templateId: '431295',
            schema: AssetSchemaType::Residential->value,
            owner: $this->player->account_id,
            population: 1,
            water: 300,
            energy: 20,
        );

        $actualValue = CheckServiceConsumptionAction::execute($stakedAssets, $asset);

        $this->assertFalse($actualValue);
    }
}
