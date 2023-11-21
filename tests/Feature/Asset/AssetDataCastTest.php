<?php

namespace Tests\Feature\Asset;

use Tests\TestCase;
use Domain\Player\Models\Player;
use Domain\Asset\Models\StakedAsset;
use Domain\Asset\Entities\Residential;
use Domain\Asset\Entities\SpecialBuild;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AssetDataCastTest extends TestCase
{
    use RefreshDatabase;

    protected Player $player;

    public function setUp(): void
    {
        parent::setUp();

        /** @var Player */
        $this->player = Player::factory()->create();
        $this->actingAs($this->player);
    }

    /** @test */
    public function it_can_cast_an_old_data_from_a_residential_staked_asset()
    {
        $oldStakedAsset = StakedAsset::factory()->create([
            'player_id' => $this->player->id,
            'asset_id' => '1099643790427',
            'data' => [
                'water' => 2,
                'energy' => 2,
                'schema' => Residential::SCHEMA_NAME,
                'population' => 2,
                'template_id' => '431287',
            ],
        ]);

        $this->assertModelExists($oldStakedAsset);
    }

    /** @test */
    public function it_can_cast_an_old_data_from_a_specialBuild_staked_asset()
    {
        $oldStakedAsset = StakedAsset::factory()->create([
            'player_id' => $this->player->id,
            'asset_id' => '1099643790427',
            'data' => [
                'schema' => SpecialBuild::SCHEMA_NAME,
                'population' => 2,
                'template_id' => '431287',
            ],
        ]);

        $this->assertModelExists($oldStakedAsset);
    }
}
