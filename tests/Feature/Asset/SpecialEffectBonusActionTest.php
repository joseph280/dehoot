<?php

namespace Tests\Feature\Asset;

use Tests\TestCase;
use Mockery\MockInterface;
use Domain\Player\Models\Player;
use Domain\Asset\Models\StakedAsset;
use Illuminate\Auth\Authenticatable;
use Domain\Shared\ValueObjects\Token;
use Domain\Asset\Effects\TheHutEffect;
use Domain\Asset\Enums\AssetSchemaType;
use Domain\Asset\Effects\FirstHootBank2;
use Domain\Asset\Effects\MajorPlazaEffect;
use Domain\Asset\ValueObjects\EffectValue;
use Domain\Asset\Effects\FirstHootBankEffect;
use Domain\Player\Actions\GetHootBalanceAction;
use Domain\Asset\Actions\SpecialEffectBonusAction;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SpecialEffectBonusActionTest extends TestCase
{
    use RefreshDatabase;

    protected Player $player;

    public function setUp(): void
    {
        parent::setUp();

        /** @var Player|Authenticatable */
        $this->player = Player::factory()->create();

        $this->residential = $this->regularResponse->residential;
    }

    /** @test */
    public function empty_data_if_no_special_build_with_effects()
    {
        StakedAsset::factory()
            ->create([
                'player_id' => $this->player->id,
                'asset_id' => $this->residential->assetId,
                'staked_at' => now()->subDay(),
            ]);

        StakedAsset::factory()
            ->create([
                'player_id' => $this->player->id,
                'asset_id' => '1099730973889',
                'staked_at' => now()->subDay(2),
            ]);

        $stakedAssets = StakedAsset::getPlayerAssetsInStaking($this->player->id);

        $bonus = SpecialEffectBonusAction::execute($stakedAssets, $this->player);

        $expected = [
            'effects' => collect([]),
            'assets' => collect([]),
        ];

        $this->assertEquals($expected, $bonus);
    }

    /** @test */
    public function adding_bonus_from_the_hut()
    {
        $stakedAsset1 = StakedAsset::factory()->create([
            'player_id' => $this->player->id,
            'asset_id' => $this->residential->assetId,
            'staked_at' => now()->subDay(),
        ]);

        $stakedAsset2 = StakedAsset::factory()->create([
            'player_id' => $this->player->id,
            'asset_id' => '1',
            'staked_at' => now()->subDay(2),
        ]);

        StakedAsset::factory()
            ->withData([
                'templateId' => TheHutEffect::TEMPLATE_ID,
                'schema' => AssetSchemaType::Special,
                'population' => 4,
            ])
            ->create([
                'player_id' => $this->player->id,
                'asset_id' => '1099712438807',
                'staked_at' => now()->subDay(2),
            ]);

        $stakedAssets = $this->player->stakedAssets;

        $bonus = SpecialEffectBonusAction::execute($stakedAssets, $this->player);

        $expected = [
            'effects' => collect([EffectValue::from(TheHutEffect::NAME, 150)]),
            'assets' => collect([$stakedAsset1, $stakedAsset2]),
        ];

        $this->assertEquals($expected['effects'], $bonus['effects']);
    }

    /** @test */
    public function no_bonus_when_only_having_the_hut()
    {
        StakedAsset::factory()
            ->withData([
                'templateId' => TheHutEffect::TEMPLATE_ID,
                'schema' => AssetSchemaType::Special,
                'population' => 4,
            ])
            ->create([
                'player_id' => $this->player->id,
                'asset_id' => '1099712438807',
                'staked_at' => now()->subDay(3),
            ]);

        $stakedAssets = StakedAsset::getPlayerAssetsInStaking($this->player->id);

        $bonus = SpecialEffectBonusAction::execute($stakedAssets, $this->player);

        $expected = [
            'effects' => collect([]),
            'assets' => collect([]),
        ];

        $this->assertEquals($expected, $bonus);
    }

    /** @test */
    public function it_generates_the_hut_bonus_for_one_asset_indicated()
    {
        $stakedAsset = StakedAsset::factory(2)->create([
            'player_id' => $this->player->id,
            'staked_at' => now()->subDay(),
        ])->first();

        StakedAsset::factory()
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

        $bonus = SpecialEffectBonusAction::execute($stakedAsset, $this->player);

        $expected = [
            'effects' => collect([EffectValue::from(TheHutEffect::NAME, 50)]),
            'assets' => collect([]),
        ];

        $this->assertEquals($expected['effects'], $bonus['effects']);
    }

    /** @test */
    public function the_hut_effect_doesnt_count_if_staking_time_in_days_is_less_than_one()
    {
        $stakedAsset = StakedAsset::factory()
            ->create([
                'player_id' => $this->player->id,
                'asset_id' => '1',
                'staked_at' => now(),
            ]);

        StakedAsset::factory()
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

        $bonus = SpecialEffectBonusAction::execute($stakedAsset, $this->player);

        $expected = [
            'effects' => collect([]),
            'assets' => collect([]),
        ];

        $expectedBonus = collect([]);

        $this->assertEquals($expected['effects'], $bonus['effects']);
    }

    /** @test */
    public function it_generates_the_hut_bonus_for_one_residential()
    {
        $stakedAsset = StakedAsset::factory()
            ->create([
                'player_id' => $this->player->id,
                'asset_id' => '1',
                'staked_at' => now()->subDay(),
            ]);

        StakedAsset::factory()
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

        $bonus = SpecialEffectBonusAction::execute($stakedAsset, $this->player);

        $expected = [
            'effects' => collect([EffectValue::from(TheHutEffect::NAME, 50)]),
            'assets' => collect([]),
        ];

        $this->assertEquals($expected['effects'], $bonus['effects']);
    }

    /** @test */
    public function it_generates_a_maximum_of_500_hoot_a_day_with_10_assets()
    {
        $stakedAssets = StakedAsset::factory(11)->create([
            'player_id' => $this->player->id,
            'staked_at' => now()->subDay(),
        ]);

        StakedAsset::factory()
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

        $bonus = SpecialEffectBonusAction::execute($stakedAssets, $this->player);

        $expected = [
            'effects' => collect([EffectValue::from(TheHutEffect::NAME, 500)]),
            'assets' => collect([]),
        ];

        $this->assertEquals($expected['effects'], $bonus['effects']);
    }

    /** @test */
    public function adding_bonus_from_major_plaza()
    {
        StakedAsset::factory()
            ->withData([
                'templateId' => MajorPlazaEffect::TEMPLATE_ID,
                'schema' => AssetSchemaType::Special,
                'population' => 0,
            ])
            ->create([
                'player_id' => $this->player->id,
                'asset_id' => '1099730973889',
                'staked_at' => now()->subDays(2),
            ]);

        StakedAsset::factory()
            ->withData([
                'templateId' => MajorPlazaEffect::TEMPLATE_ID,
                'schema' => AssetSchemaType::Special,
                'population' => 0,
            ])
            ->create([
                'player_id' => $this->player->id,
                'asset_id' => '1099730973884',
                'staked_at' => now()->subDay(),
            ]);

        $stakedAssets = StakedAsset::getPlayerAssetsInStaking($this->player->id);

        $bonus = SpecialEffectBonusAction::execute($stakedAssets, $this->player);

        $expected = [
            'effects' => collect([EffectValue::from(MajorPlazaEffect::NAME, 500)]),
            'assets' => collect([]),
        ];

        $this->assertEquals($expected['effects'], $bonus['effects']);
    }

    /** @test */
    public function it_generates_bonus_for_one_major_plaza_in_staking()
    {
        $stakedAsset = StakedAsset::factory()
            ->withData([
                'templateId' => MajorPlazaEffect::TEMPLATE_ID,
                'schema' => AssetSchemaType::Special,
                'population' => 0,
            ])
            ->create([
                'player_id' => $this->player->id,
                'asset_id' => '1099730973889',
                'staked_at' => now()->subDay(),
            ]);

        $bonus = SpecialEffectBonusAction::execute($stakedAsset, $this->player);

        $expected = [
            'effects' => collect([EffectValue::from(MajorPlazaEffect::NAME, 250)]),
            'assets' => collect([]),
        ];

        $this->assertEquals($expected['effects'], $bonus['effects']);
    }

    /** @test */
    public function adding_1_percent_bonus_for_one_asset_and_first_hoot_bank_in_staking()
    {
        StakedAsset::factory()
            ->create([
                'player_id' => $this->player->id,
                'asset_id' => $this->residential->assetId,
                'staked_at' => now()->subDay(2),
            ]);

        StakedAsset::factory()
            ->withData([
                'templateId' => FirstHootBankEffect::TEMPLATE_ID,
                'schema' => AssetSchemaType::Special,
                'population' => 0,
            ])
            ->create([
                'player_id' => $this->player->id,
                'asset_id' => '1099723726022',
                'staked_at' => now()->subDay(),
            ]);

        $this->mock(GetHootBalanceAction::class, function (MockInterface $mock) {
            $mock->shouldReceive('handle')->andReturn(Token::from(45000.0));
        });

        $stakedAssets = StakedAsset::getPlayerAssetsInStaking($this->player->id);

        $bonus = SpecialEffectBonusAction::execute($stakedAssets, $this->player);

        $expected = [
            'effects' => collect([EffectValue::from(FirstHootBankEffect::NAME, 450)]),
            'assets' => collect([]),
        ];

        $this->assertEquals($expected['effects'], $bonus['effects']);
    }

    /** @test */
    public function adding_500_max_bonus_from_first_hoot_bank()
    {
        StakedAsset::factory()
            ->withData([
                'templateId' => FirstHootBankEffect::TEMPLATE_ID,
                'schema' => AssetSchemaType::Special,
                'population' => 0,
            ])
            ->create([
                'player_id' => $this->player->id,
                'asset_id' => '1099723726021',
                'staked_at' => now()->subDay(2),
            ]);

        StakedAsset::factory()
            ->withData([
                'templateId' => FirstHootBankEffect::TEMPLATE_ID,
                'schema' => AssetSchemaType::Special,
                'population' => 0,
            ])
            ->create([
                'player_id' => $this->player->id,
                'asset_id' => '1099723726022',
                'staked_at' => now()->subDay(),
            ]);

        $this->mock(GetHootBalanceAction::class, function (MockInterface $mock) {
            $mock->shouldReceive('handle')->andReturn(Token::from(70000.0));
        });

        $stakedAssets = StakedAsset::getPlayerAssetsInStaking($this->player->id);

        $bonus = SpecialEffectBonusAction::execute($stakedAssets, $this->player);

        $expected = [
            'effects' => collect([EffectValue::from(FirstHootBankEffect::NAME, 700)]),
            'assets' => collect([]),
        ];

        $this->assertEquals($expected['effects'], $bonus['effects']);
    }

    /** @test */
    public function adding_500_successive_bonuses_from_first_hoot_banks()
    {
        StakedAsset::factory()
            ->withData([
                'templateId' => FirstHootBankEffect::TEMPLATE_ID,
                'schema' => AssetSchemaType::Special,
                'population' => 0,
            ])
            ->create([
                'player_id' => $this->player->id,
                'asset_id' => '1099723726021',
                'staked_at' => now()->subDay(3),
            ]);

        StakedAsset::factory()
            ->withData([
                'templateId' => FirstHootBankEffect::TEMPLATE_ID,
                'schema' => AssetSchemaType::Special,
                'population' => 0,
            ])
            ->create([
                'player_id' => $this->player->id,
                'asset_id' => '1099723726022',
                'staked_at' => now()->subDay(),
            ]);

        StakedAsset::factory()
            ->withData([
                'templateId' => FirstHootBankEffect::TEMPLATE_ID,
                'schema' => AssetSchemaType::Special,
                'population' => 0,
            ])
            ->create([
                'player_id' => $this->player->id,
                'asset_id' => '1099723726013',
                'staked_at' => now()->subDay(2),
            ]);

        $this->mock(GetHootBalanceAction::class, function (MockInterface $mock) {
            $mock->shouldReceive('handle')->andReturn(Token::from(160000.0));
        });

        $stakedAssets = StakedAsset::getPlayerAssetsInStaking($this->player->id);

        $bonus = SpecialEffectBonusAction::execute($stakedAssets, $this->player);

        $expected = [
            'effects' => collect([EffectValue::from(FirstHootBankEffect::NAME, 3000)]),
            'assets' => collect([]),
        ];

        $this->assertEquals($expected['effects'], $bonus['effects']);
    }

    /** @test */
    public function single_bank_can_generated_bonus()
    {
        $bank = StakedAsset::factory()
            ->withData([
                'templateId' => FirstHootBankEffect::TEMPLATE_ID,
                'schema' => AssetSchemaType::Special,
                'population' => 0,
            ])
            ->create([
                'player_id' => $this->player->id,
                'asset_id' => '1099723726022',
                'staked_at' => now()->subDay(),
            ]);

        $this->mock(GetHootBalanceAction::class, function (MockInterface $mock) {
            $mock->shouldReceive('handle')->andReturn(Token::from(50000.0));
        });

        $bonus = SpecialEffectBonusAction::execute($bank, $this->player);

        $expected = [
            'effects' => collect([EffectValue::from(FirstHootBankEffect::NAME, 500)]),
            'assets' => collect([$bank]),
        ];

        $this->assertEquals($expected, $bonus);
    }

    /** @test */
    public function single_bank_lv_2_can_generated_bonus()
    {
        $bank = StakedAsset::factory()->withData([
            'templateId' => FirstHootBank2::TEMPLATE_ID,
            'schema' => AssetSchemaType::Special,
            'population' => 0,
        ])->create([
            'player_id' => $this->player->id,
            'asset_id' => '1099723726022',
            'staked_at' => now()->subDay(),
        ]);

        $this->mock(GetHootBalanceAction::class, function (MockInterface $mock) {
            $mock->shouldReceive('handle')->andReturn(Token::from(50000.0));
        });

        $actual = SpecialEffectBonusAction::execute($bank, $this->player);

        $expected = [
            'effects' => collect([EffectValue::from(FirstHootBank2::NAME, 1000)]),
            'assets' => collect([$bank]),
        ];

        $this->assertEquals($expected, $actual);
    }

    /** @test */
    public function adding_successive_bonuses_from_first_hoot_banks_lv_2()
    {
        StakedAsset::factory(3)->withData([
            'templateId' => FirstHootBank2::TEMPLATE_ID,
            'schema' => AssetSchemaType::Special,
            'population' => 0,
        ])->create([
            'player_id' => $this->player->id,
            'asset_id' => '1099723726021',
            'staked_at' => now()->subDay(3),
        ]);

        $this->mock(GetHootBalanceAction::class, function (MockInterface $mock) {
            $mock->shouldReceive('handle')->andReturn(Token::from(160000.0));
        });

        $stakedAssets = StakedAsset::getPlayerAssetsInStaking($this->player->id);

        $actual = SpecialEffectBonusAction::execute($stakedAssets, $this->player);

        $expected = [
            'effects' => collect([EffectValue::from(FirstHootBank2::NAME, 9000)]),
            'assets' => collect([]),
        ];

        $this->assertEquals($expected['effects'], $actual['effects']);
    }

    /** @test */
    public function single_hut_generate_bonus_from_unstake()
    {
        StakedAsset::factory(2)->create([
            'player_id' => $this->player->id,
            'staked_at' => now()->subDay(),
        ]);

        $hut = StakedAsset::factory()
            ->withData([
                'templateId' => TheHutEffect::TEMPLATE_ID,
                'schema' => AssetSchemaType::Special,
                'population' => 4,
            ])
            ->create([
                'player_id' => $this->player->id,
                'asset_id' => '1099712438807',
                'staked_at' => now()->subDay(2),
            ]);

        $bonus = SpecialEffectBonusAction::execute($hut, $this->player);

        $expected = [
            'effects' => collect([EffectValue::from(TheHutEffect::NAME, 100)]),
            'assets' => collect([$hut]),
        ];

        $this->assertEquals($expected, $bonus);
    }

    /** @test */
    public function single_hut_cant_generate_bonus_from_others_huts()
    {
        $hut = StakedAsset::factory(3)
            ->withData([
                'templateId' => TheHutEffect::TEMPLATE_ID,
                'schema' => AssetSchemaType::Special,
                'population' => 4,
            ])
            ->create([
                'player_id' => $this->player->id,
                'asset_id' => '1099712438807',
                'staked_at' => now()->subDay(2),
            ])->first();

        $bonus = SpecialEffectBonusAction::execute($hut, $this->player);

        $expected = [
            'effects' => collect([]),
            'assets' => collect([]),
        ];

        $this->assertEquals($expected, $bonus);
    }

    /** @test */
    public function single_major_plaza_can_generate_bonus()
    {
        $majorPlaza = StakedAsset::factory()
            ->withData([
                'templateId' => MajorPlazaEffect::TEMPLATE_ID,
                'schema' => AssetSchemaType::Special,
                'population' => 0,
            ])
            ->create([
                'player_id' => $this->player->id,
                'asset_id' => '1099712438807',
                'staked_at' => now()->subDay(1),
            ]);

        $bonus = SpecialEffectBonusAction::execute($majorPlaza, $this->player);

        $expected = [
            'effects' => collect([EffectValue::from(MajorPlazaEffect::NAME, 250)]),
            'assets' => collect([$majorPlaza]),
        ];

        $this->assertEquals($expected, $bonus);
    }
}
