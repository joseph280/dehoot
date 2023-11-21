<?php

namespace Tests\Feature\Asset\SpecialBuild;

use Tests\TestCase;
use Mockery\MockInterface;
use Domain\Player\Models\Player;
use Domain\Asset\Models\StakedAsset;
use Domain\Shared\ValueObjects\Token;
use Domain\Asset\Enums\AssetSchemaType;
use Domain\Asset\Effects\FirstHootBankEffect;
use Domain\Player\Actions\GetHootBalanceAction;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FirstHootBankTest extends TestCase
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
    public function hoot_bank_effect_for_one_bank_with_one_day_in_staking()
    {
        $bank = StakedAsset::factory()
            ->withData([
                'templateId' => FirstHootBankEffect::TEMPLATE_ID,
                'schema' => AssetSchemaType::Special,
                'population' => 4,
            ])
            ->create([
                'player_id' => $this->player->id,
                'asset_id' => '1099712438807',
                'staked_at' => now()->subDay()->subHour(),
            ]);

        $this->mock(GetHootBalanceAction::class, function (MockInterface $mock) {
            $mock->shouldReceive('handle')->andReturn(Token::from(45000.0));
        });

        $bonus = FirstHootBankEffect::effect(collect([$bank]), $this->player);
        $expected = [
            'bonus' => 450.0,
            'assets' => collect([$bank]),
        ];
        $this->assertEquals($expected, $bonus);
    }

    /** @test */
    public function hoot_bank_effect_is_accumulative_by_days()
    {
        $bank = StakedAsset::factory()
            ->withData([
                'templateId' => FirstHootBankEffect::TEMPLATE_ID,
                'schema' => AssetSchemaType::Special,
                'population' => 4,
            ])
            ->create([
                'player_id' => $this->player->id,
                'asset_id' => '1099712438807',
                'staked_at' => now()->subDays(2),
            ]);

        $this->mock(GetHootBalanceAction::class, function (MockInterface $mock) {
            $mock->shouldReceive('handle')->andReturn(Token::from(50000.0));
        });

        $bonus = FirstHootBankEffect::effect(collect([$bank]), $this->player);
        $expected = [
            'bonus' => 1000,
            'assets' => collect([$bank]),
        ];
        $this->assertEquals($expected, $bonus);
    }

    /** @test */
    public function hoot_bank_effect_calculates_1_percent()
    {
        $bank = StakedAsset::factory()
            ->withData([
                'templateId' => FirstHootBankEffect::TEMPLATE_ID,
                'schema' => AssetSchemaType::Special,
                'population' => 4,
            ])
            ->create([
                'player_id' => $this->player->id,
                'asset_id' => '1099712438807',
                'staked_at' => now()->subDays(2),
            ]);

        $this->mock(GetHootBalanceAction::class, function (MockInterface $mock) {
            $mock->shouldReceive('handle')->andReturn(Token::from(47000.0));
        });

        $bonus = FirstHootBankEffect::effect(collect([$bank]), $this->player);
        $expected = [
            'bonus' => 940.0,
            'assets' => collect([$bank]),
        ];
        $this->assertEquals($expected, $bonus);
    }

    /** @test */
    public function hoot_bank_effect_is_zero_for_one_bank_with_less_than_one_day_in_staking()
    {
        $bank = StakedAsset::factory()
            ->withData([
                'templateId' => FirstHootBankEffect::TEMPLATE_ID,
                'schema' => AssetSchemaType::Special,
                'population' => 4,
            ])
            ->create([
                'player_id' => $this->player->id,
                'asset_id' => '1099712438807',
                'staked_at' => now(),
            ]);

        $this->mock(GetHootBalanceAction::class, function (MockInterface $mock) {
            $mock->shouldReceive('handle')->andReturn(Token::from(45000.0));
        });

        $bonus = FirstHootBankEffect::effect(collect([$bank]), $this->player);
        $expected = [
            'bonus' => 0,
            'assets' => collect([]),
        ];
        $this->assertEquals($expected, $bonus);
    }

    /** @test */
    public function two_banks_generates_bonus_if_player_has_correct_balance()
    {
        $banks = StakedAsset::factory(2)
            ->withData([
                'templateId' => FirstHootBankEffect::TEMPLATE_ID,
                'schema' => AssetSchemaType::Special,
                'population' => 4,
            ])
            ->create([
                'player_id' => $this->player->id,
                'asset_id' => '1099712438807',
                'staked_at' => now()->subDay(),
            ]);

        $this->mock(GetHootBalanceAction::class, function (MockInterface $mock) {
            $mock->shouldReceive('handle')->andReturn(Token::from(100000.0));
        });

        $bonus = FirstHootBankEffect::effect($banks, $this->player);
        $expected = [
            'bonus' => '1000',
            'assets' => $banks,
        ];
        $this->assertEquals($expected, $bonus);
    }

    /** @test */
    public function three_banks_generates_bonus_if_player_has_correct_balance_with_two_days_of_staking()
    {
        $banks = StakedAsset::factory(3)
            ->withData([
                'templateId' => FirstHootBankEffect::TEMPLATE_ID,
                'schema' => AssetSchemaType::Special,
                'population' => 4,
            ])
            ->create([
                'player_id' => $this->player->id,
                'asset_id' => '1099712438807',
                'staked_at' => now()->subDays(2),
            ]);

        $this->mock(GetHootBalanceAction::class, function (MockInterface $mock) {
            $mock->shouldReceive('handle')->andReturn(Token::from(150000.0));
        });

        $bonus = FirstHootBankEffect::effect($banks, $this->player);
        $expected = [
            'bonus' => '3000',
            'assets' => $banks,
        ];
        $this->assertEquals($expected, $bonus);
    }

    /** @test */
    public function it_calculates_1_percent_of_a_single_bank_if_balance_is_bellow_maximum_limit()
    {
        $banks = StakedAsset::factory(2)
            ->withData([
                'templateId' => FirstHootBankEffect::TEMPLATE_ID,
                'schema' => AssetSchemaType::Special,
                'population' => 4,
            ])
            ->create([
                'player_id' => $this->player->id,
                'asset_id' => '1099712438807',
                'staked_at' => now()->subDay(),
            ]);

        $this->mock(GetHootBalanceAction::class, function (MockInterface $mock) {
            $mock->shouldReceive('handle')->andReturn(Token::from(45000.0));
        });

        $bonus = FirstHootBankEffect::effect($banks, $this->player);
        $this->assertEquals([
            'bonus' => 450,
            'assets' => $banks,
        ], $bonus);
    }
}
