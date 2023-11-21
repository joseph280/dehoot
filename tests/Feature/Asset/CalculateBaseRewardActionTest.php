<?php

namespace Tests\Feature\Asset;

use Tests\TestCase;
use Mockery\MockInterface;
use Domain\Player\Models\Player;
use Domain\Asset\Models\StakedAsset;
use Domain\Asset\Enums\AssetSchemaType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Domain\Asset\Actions\CalculateBaseRewardAction;
use Domain\Atomic\Interfaces\AtomicApiManagerInterface;

class CalculateBaseRewardActionTest extends TestCase
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
    public function it_calculates_base_reward()
    {
        $stakedAsset = StakedAsset::factory()
            ->withData(['population' => '2', 'template_id' => '521643', 'schema' => AssetSchemaType::Residential])
            ->create([
                'player_id' => $this->player,
                'staked_at' => now()->subDay(),
            ]);

        $this->mock(AtomicApiManagerInterface::class, function (MockInterface $mock) {
            $mock->shouldReceive('assets')->andReturn($this->noDataResponse);
        });

        $amount = CalculateBaseRewardAction::execute($stakedAsset);

        $this->assertEquals(2.0, $amount->value);
    }
}
