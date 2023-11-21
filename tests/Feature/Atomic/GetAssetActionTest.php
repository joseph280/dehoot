<?php

namespace Tests\Feature\Atomic;

use Tests\TestCase;
use Mockery\MockInterface;
use Domain\Player\Models\Player;
use Illuminate\Auth\Authenticatable;
use Domain\Asset\Entities\Residential;
use Domain\Asset\Entities\SpecialBuild;
use Domain\Asset\ValueObjects\AssetData;
use Domain\Atomic\Actions\GetAssetAction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Domain\Atomic\Interfaces\AtomicApiManagerInterface;

class GetAssetActionTest extends TestCase
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
    public function it_returns_a_valid_asset_data_value_object()
    {
        $this->mock(AtomicApiManagerInterface::class, function (MockInterface $mock) {
            $mock->shouldReceive('asset')->andReturn($this->singleAssetAtomicResponse);
        });

        $templateId = data_get($this->singleAssetAtomicResponse, 'data.0.template.template_id');

        /** @var AssetData */
        $assetData = GetAssetAction::execute($this->player, $templateId);

        $expectedAssetData = new AssetData(
            templateId: $templateId,
            schema: Residential::SCHEMA_NAME,
            population: 1,
        );

        $this->assertEquals($expectedAssetData, $assetData);
    }

    /** @test */
    public function it_returns_a_valid_asset_data_special_building_object()
    {
        $this->mock(AtomicApiManagerInterface::class, function (MockInterface $mock) {
            $mock->shouldReceive('asset')->andReturn($this->singleSpecialAssetAtomicResponse);
        });

        $templateId = data_get($this->singleSpecialAssetAtomicResponse, 'data.0.template.template_id');

        /** @var AssetData */
        $assetData = GetAssetAction::execute($this->player, $templateId);

        $expectedAssetData = new AssetData(
            templateId: $templateId,
            schema: SpecialBuild::SCHEMA_NAME,
            population: 0,
        );

        $this->assertEquals($expectedAssetData, $assetData);
    }

    /** @test */
    public function it_returns_an_exception_if_asset_not_found()
    {
        $this->mock(AtomicApiManagerInterface::class, function (MockInterface $mock) {
            $mock->shouldReceive('asset')->andReturn($this->noDataResponse);
        });

        $this->expectExceptionMessage("The asset doesn't exist");

        GetAssetAction::execute($this->player, '');

        $this->assertDatabaseCount('staked_asset', 0);
    }
}
