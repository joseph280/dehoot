<?php

namespace Tests\Feature\Asset;

use Tests\TestCase;
use Domain\Player\Models\Player;
use Domain\Asset\Entities\Service;
use Domain\Asset\Models\StakedAsset;
use Illuminate\Auth\Authenticatable;
use Domain\Asset\Enums\AssetSchemaType;
use Domain\Player\ValueObjects\PlayerServices;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Domain\Asset\Actions\GetServiceConsumptionAction;

class GetServiceConsumptionActionTest extends TestCase
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
    public function it_gets_service_consumption()
    {
        $this->addServices();

        StakedAsset::factory()
            ->withData([
                'population' => '1',
                'water' => '175',
                'energy' => '175',
                'schema' => AssetSchemaType::Residential,
                'templateId' => 151243,
            ])
            ->create([
                'player_id' => $this->player->id,
            ]);

        $stakedAssets = $this->player->stakedAssets;

        /** @var PlayerServices */
        $consumption = GetServiceConsumptionAction::execute($stakedAssets);

        $this->assertEquals('175', $consumption->water->current);
        $this->assertEquals('350', $consumption->water->total);
        $this->assertEquals('50.00%', $consumption->water->percentage);
        $this->assertEquals('175', $consumption->energy->current);
        $this->assertEquals('350', $consumption->energy->total);
        $this->assertEquals('50.00%', $consumption->energy->percentage);
    }

    /** @test */
    public function if_user_has_no_assets_it_returns_values_of_zero_consumption()
    {
        /** @var PlayerServices */
        $consumption = GetServiceConsumptionAction::execute($this->player->stakedAssets);

        $this->assertEquals('0', $consumption->water->current);
        $this->assertEquals('0', $consumption->water->total);
        $this->assertEquals('0%', $consumption->water->percentage);
        $this->assertEquals('0', $consumption->energy->current);
        $this->assertEquals('0', $consumption->energy->total);
        $this->assertEquals('0%', $consumption->energy->percentage);
    }

    protected function addServices()
    {
        StakedAsset::factory()
            ->withData([
                'capacity' => '350',
                'type' => Service::WATER_TYPE,
                'schema' => AssetSchemaType::Service,
            ])
            ->create([
                'player_id' => $this->player->id,
            ]);

        StakedAsset::factory()
            ->withData([
                'capacity' => '350',
                'type' => Service::ENERGY_TYPE,
                'schema' => AssetSchemaType::Service,
            ])
            ->create([
                'player_id' => $this->player->id,
            ]);
    }
}
