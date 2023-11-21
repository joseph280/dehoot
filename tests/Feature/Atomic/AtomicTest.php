<?php

namespace Tests\Feature\Atomic;

use Tests\TestCase;
use Domain\Player\Models\Player;
use Illuminate\Auth\Authenticatable;
use Illuminate\Support\Facades\Http;
use Domain\Atomic\Api\AtomicApiManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Domain\Atomic\Interfaces\AtomicApiManagerInterface;

class AtomicTest extends TestCase
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
    public function it_calls_special_assets_method_and_returns_user_special_assets()
    {
        /** @var AtomicApiManager */
        $atomicApi = app(AtomicApiManagerInterface::class);

        Http::fake([
            'https://wax.api.atomicassets.io/*' => Http::response($this->atomicSpecialBuildResponse, 200, []),
        ]);

        $response = $atomicApi->specialAssets($this->player);

        $this->assertEquals($this->atomicSpecialBuildResponse, $response);
    }

    /** @test */
    public function it_calls_assets_method_and_returns_user_assets()
    {
        /** @var AtomicApiManager */
        $atomicApi = app(AtomicApiManagerInterface::class);

        Http::fake([
            'https://wax.api.atomicassets.io/*' => Http::response($this->atomicResponse, 200, []),
        ]);

        $response = $atomicApi->assets($this->player);

        $this->assertEquals($this->atomicResponse, $response);
    }

    /** @test */
    public function it_calls_asset_method_and_returns_single_asset()
    {
        /** @var AtomicApiManager */
        $atomicApi = app(AtomicApiManagerInterface::class);

        Http::fake([
            'https://wax.api.atomicassets.io/*' => Http::response($this->singleAssetAtomicResponse, 200, []),
        ]);

        $templateId = data_get($this->singleAssetAtomicResponse, 'data.0.template.template_id');

        $response = $atomicApi->asset($this->player, $templateId);

        $this->assertEquals($this->singleAssetAtomicResponse, $response);
    }
}
