<?php

namespace Tests\Feature\Asset\SpecialBuild;

use Tests\TestCase;
use Domain\Player\Models\Player;
use Domain\Asset\Models\StakedAsset;
use Domain\Asset\Enums\AssetSchemaType;
use Domain\Asset\Effects\MajorPlazaEffect;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MajorPlazaTest extends TestCase
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
    public function major_plaza_bonus_for_one_day_in_staking()
    {
        $plaza = StakedAsset::factory()
            ->withData([
                'templateId' => MajorPlazaEffect::TEMPLATE_ID,
                'schema' => AssetSchemaType::Special,
                'population' => 4,
            ])
            ->create([
                'player_id' => $this->player->id,
                'asset_id' => '1099712438807',
                'staked_at' => now()->subDay()->subHour(),
            ]);

        $bonus = MajorPlazaEffect::effect($plaza);
        $expected = [
            'bonus' => 250.0,
            'assets' => collect([$plaza]),
        ];
        $this->assertEquals($expected, $bonus);
    }

    /** @test */
    public function major_plaza_bonus_is_zero_when_it_has_less_than_one_day_in_staking()
    {
        $plaza = StakedAsset::factory()
            ->withData([
                'templateId' => MajorPlazaEffect::TEMPLATE_ID,
                'schema' => AssetSchemaType::Special,
                'population' => 4,
            ])
            ->create([
                'player_id' => $this->player->id,
                'asset_id' => '1099712438807',
                'staked_at' => now(),
            ]);

        $bonus = MajorPlazaEffect::effect($plaza);
        $expected = [
            'bonus' => 0.0,
            'assets' => collect([$plaza]),
        ];
        $this->assertEquals($expected, $bonus);
    }

    /** @test */
    public function major_plaza_bonus_is_multiplied_by_days_in_staking()
    {
        $plaza = StakedAsset::factory()
            ->withData([
                'templateId' => MajorPlazaEffect::TEMPLATE_ID,
                'schema' => AssetSchemaType::Special,
                'population' => 4,
            ])
            ->create([
                'player_id' => $this->player->id,
                'asset_id' => '1099712438807',
                'staked_at' => now()->subdays(3),
            ]);

        $bonus = MajorPlazaEffect::effect($plaza);
        $expected = [
            'bonus' => 750.0,
            'assets' => collect([$plaza]),
        ];
        $this->assertEquals($expected, $bonus);
    }
}
