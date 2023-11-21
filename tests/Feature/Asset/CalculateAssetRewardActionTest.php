<?php

namespace Tests\Feature\Asset;

use Tests\TestCase;
use Mockery\MockInterface;
use Domain\Player\Models\Player;
use Domain\Asset\Models\StakedAsset;
use Domain\Asset\Enums\AssetSchemaType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Domain\Asset\Actions\CalculateAssetRewardAction;
use Domain\Atomic\Interfaces\AtomicApiManagerInterface;

class CalculateAssetRewardActionTest extends TestCase
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
    public function it_calculates_base_reward_with_bonus()
    {
        $stakedAsset = StakedAsset::factory()
            ->withData(
                ['population' => '2', 'template_id' => '521643', 'schema' => AssetSchemaType::Residential],
            )
            ->create([
                'player_id' => $this->player->id,
                'staked_at' => now()->subDay(),
            ]);

        $this->mock(AtomicApiManagerInterface::class, function (MockInterface $mock) {
            $mock->shouldReceive('assets')->andReturn($this->atomicSpecialBuildResponse);
        });

        $this->player->is_vip = true;

        $amount = CalculateAssetRewardAction::execute($stakedAsset, $this->player->is_vip);

        $this->assertEquals(3.0, $amount->value);
    }
}
