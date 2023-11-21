<?php

namespace Tests\Feature\Asset\SpecialBuild;

use Tests\TestCase;
use Domain\Player\Models\Player;
use Domain\Asset\Models\StakedAsset;
use Domain\Asset\Effects\TheHutEffect;
use Domain\Asset\Enums\AssetSchemaType;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TheHutTest extends TestCase
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
    public function hut_effects_for_one_residential_with_one_day_of_reward()
    {
        $hut = StakedAsset::factory()
            ->withData([
                'templateId' => TheHutEffect::TEMPLATE_ID,
                'schema' => AssetSchemaType::Special,
                'population' => 4,
            ])
            ->create([
                'player_id' => $this->player->id,
                'asset_id' => '1099712438807',
                'staked_at' => now()->subDay()->subHour(),
            ]);

        StakedAsset::factory()->create([
            'player_id' => $this->player->id,
            'asset_id' => '2',
            'staked_at' => now()->subDay(),
        ]);

        $stakedAssets = StakedAsset::where('player_id', $this->player->id)->get();

        $bonus = TheHutEffect::effect($hut, $stakedAssets);
        $expected = [
            'bonus' => '50',
            'assets' => collect([$hut]),
        ];
        $this->assertEquals($expected, $bonus);
    }

    /** @test */
    public function hut_effects_is_zero_for_residential_with_less_than_one_day_in_staking()
    {
        $hut = StakedAsset::factory()
            ->withData([
                'templateId' => TheHutEffect::TEMPLATE_ID,
                'schema' => AssetSchemaType::Special,
                'population' => 4,
            ])
            ->create([
                'player_id' => $this->player->id,
                'asset_id' => '1099712438807',
                'staked_at' => now()->subDay(),
            ]);

        StakedAsset::factory()->create([
            'player_id' => $this->player->id,
            'asset_id' => '2',
            'staked_at' => now(),
        ]);

        $stakedAssets = StakedAsset::where('player_id', $this->player->id)->get();

        $bonus = TheHutEffect::effect($hut, $stakedAssets);
        $expected = [
            'bonus' => 0,
            'assets' => collect([$hut]),
        ];
        $this->assertEquals($expected, $bonus);
    }

    /** @test */
    public function hut_effects_is_zero_for_residential_and_hut_with_less_than_one_day_in_staking()
    {
        $hut = StakedAsset::factory()
            ->withData([
                'templateId' => TheHutEffect::TEMPLATE_ID,
                'schema' => AssetSchemaType::Special,
                'population' => 4,
            ])
            ->create([
                'player_id' => $this->player->id,
                'asset_id' => '1099712438807',
                'staked_at' => now(),
            ]);

        StakedAsset::factory()->create([
            'player_id' => $this->player->id,
            'asset_id' => '2',
            'staked_at' => now(),
        ]);

        $stakedAssets = StakedAsset::where('player_id', $this->player->id)->get();

        $bonus = TheHutEffect::effect($hut, $stakedAssets);
        $expected = [
            'bonus' => 0,
            'assets' => collect([$hut]),
        ];
        $this->assertEquals($expected, $bonus);
    }

    /** @test */
    public function hut_effects_work_for_one_residential_staked_one_day_later()
    {
        $hut = StakedAsset::factory()
            ->withData([
                'templateId' => TheHutEffect::TEMPLATE_ID,
                'schema' => AssetSchemaType::Special,
                'population' => 4,
            ])
            ->create([
                'player_id' => $this->player->id,
                'asset_id' => '1099712438807',
                'staked_at' => now()->subDays(2),
            ]);

        StakedAsset::factory()->create([
            'player_id' => $this->player->id,
            'asset_id' => '2',
            'staked_at' => now()->subDay(),
        ]);

        $stakedAssets = StakedAsset::where('player_id', $this->player->id)->get();

        $bonus = TheHutEffect::effect($hut, $stakedAssets);
        $expected = [
            'bonus' => '50',
            'assets' => collect([$hut]),
        ];
        $this->assertEquals($expected, $bonus);
    }

    /** @test */
    public function maximum_assets_to_calculate_bonus_is_ten_residentials()
    {
        $hut = StakedAsset::factory()
            ->withData([
                'templateId' => TheHutEffect::TEMPLATE_ID,
                'schema' => AssetSchemaType::Special,
                'population' => 4,
            ])
            ->create([
                'player_id' => $this->player->id,
                'asset_id' => '1099712438807',
                'staked_at' => now()->subDay(),
            ]);

        StakedAsset::factory(11)->create([
            'player_id' => $this->player->id,
            'asset_id' => '2',
            'staked_at' => now()->subDay(),
        ]);

        $stakedAssets = StakedAsset::where('player_id', $this->player->id)->get();

        $bonus = TheHutEffect::effect($hut, $stakedAssets);
        $expected = [
            'bonus' => '500',
            'assets' => collect([$hut]),
        ];
        $this->assertEquals($expected, $bonus);
    }

    /** @test */
    public function staked_at_dates_are_considered_on_effect_bonus_selection()
    {
        $hut = StakedAsset::factory()
            ->withData([
                'templateId' => TheHutEffect::TEMPLATE_ID,
                'schema' => AssetSchemaType::Special,
                'population' => 4,
            ])
            ->create([
                'player_id' => $this->player->id,
                'asset_id' => '1099712438807',
                'staked_at' => now()->subDays(6),
            ]);

        /** Considered for bonus*/
        StakedAsset::factory(4)->create([
            'player_id' => $this->player->id,
            'asset_id' => '2',
            'staked_at' => now()->subDay()->subHours(),
        ]);

        StakedAsset::factory(6)->create([
            'player_id' => $this->player->id,
            'asset_id' => '2',
            'staked_at' => now()->subDays(4),
        ]);

        /** Not desired for bonus*/
        StakedAsset::factory(2)->create([
            'player_id' => $this->player->id,
            'asset_id' => '2',
            'staked_at' => now()->subDay(),
        ]);

        StakedAsset::factory(2)->create([
            'player_id' => $this->player->id,
            'asset_id' => '2',
            'staked_at' => now(),
        ]);

        $stakedAssets = StakedAsset::where('player_id', $this->player->id)->get();

        $bonus = TheHutEffect::effect($hut, $stakedAssets);
        $expected = [
            'bonus' => '1600',
            'assets' => collect([$hut]),
        ];
        $this->assertEquals($expected, $bonus);
    }
}
