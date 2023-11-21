<?php

namespace Tests\Feature\Asset;

use Tests\TestCase;
use Mockery\MockInterface;
use Domain\Player\Models\Player;
use Domain\Asset\Models\StakedAsset;
use Illuminate\Auth\Authenticatable;
use Domain\Shared\ValueObjects\Token;
use Domain\Shared\ValueObjects\Reward;
use Domain\Asset\Enums\AssetSchemaType;
use Domain\Asset\Actions\GetRewardAction;
use Domain\Asset\Effects\MajorPlazaEffect;
use Domain\Asset\ValueObjects\EffectValue;
use Domain\Player\Repositories\PlayerRepository;
use Domain\Asset\Actions\CalculateVIPBonusAction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Domain\Asset\Actions\CalculateBaseRewardAction;
use Domain\Asset\Collections\StakedAssetCollection;

class GetRewardActionTest extends TestCase
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
    public function it_returns_assets_rewards()
    {
        $stakedAssets = StakedAsset::factory(3)
            ->withData(['population' => '3', 'schema' => AssetSchemaType::Residential, 'template_id' => 2323423])
            ->create([
                'player_id' => $this->player->id,
                'staked_at' => now()->subDay(),
            ]);

        $this->mock(
            PlayerRepository::class,
            fn (MockInterface $mock) => $mock
                ->shouldReceive('getIsVIP')
                ->andReturn(false)
        );

        /** @var Reward */
        $actualReward = GetRewardAction::execute($stakedAssets, $this->player);

        $expectedReward = new Reward(
            staked: new Token(9.0),
            bonus: Token::from(0.0),
            tax: Token::from(0.45),
            total: Token::from(8.55),
            stakedAssets: $stakedAssets,
            effects: collect([]),
        );

        $this->assertEquals($expectedReward, $actualReward);
    }

    /** @test */
    public function it_returns_reward_of_assets_with_more_than_one_day()
    {
        $assets = StakedAsset::factory(3)
            ->withData(['population' => '3', 'schema' => AssetSchemaType::Residential, 'template_id' => 2323423])
            ->create([
                'player_id' => $this->player->id,
                'staked_at' => now()->subDays(3),
            ]);

        $this->mock(
            PlayerRepository::class,
            fn (MockInterface $mock) => $mock
                ->shouldReceive('getIsVIP')
                ->andReturn(false)
        );

        /** @var Reward */
        $actualReward = GetRewardAction::execute($assets, $this->player);

        $expectedReward = new Reward(
            staked: new Token(27.0),
            bonus: Token::from(0.0),
            tax: Token::from(1.35),
            total: Token::from(25.65),
            stakedAssets: $assets,
            effects: collect([]),
        );

        $this->assertEquals($expectedReward, $actualReward);
    }

    /** @test */
    public function it_returns_a_zero_reward()
    {
        $assets = StakedAsset::factory(3)->create([
            'player_id' => $this->player->id,
            'staked_at' => now(),
        ]);

        $this->mock(
            PlayerRepository::class,
            fn (MockInterface $mock) => $mock
                ->shouldReceive('getIsVIP')
                ->andReturn(false)
        );

        $this->mock(
            CalculateBaseRewardAction::class,
            fn (MockInterface $mock) => $mock
                ->shouldReceive('handle')
                ->andReturn(Token::from(0.0))
        );

        $this->mock(
            CalculateVIPBonusAction::class,
            fn (MockInterface $mock) => $mock
                ->shouldReceive('handle')
                ->andReturn(0.0)
        );

        /** @var Reward */
        $actualReward = GetRewardAction::execute($assets, $this->player);

        $expectedReward = new Reward(
            staked: new Token(0.0),
            bonus: Token::from(0.0),
            tax: Token::from(0.0),
            total: Token::from(0.0),
            stakedAssets: new StakedAssetCollection([]),
            effects: collect([]),
        );

        $this->assertEquals($expectedReward, $actualReward);
    }

    /** @test */
    public function test_reward_is_zero_if_asset_has_been_staked_for_less_than_one_day()
    {
        $assets = StakedAsset::factory(3)
            ->withData(['population' => '99', 'schema' => AssetSchemaType::Residential, 'template_id' => 2323423])
            ->create([
                'player_id' => $this->player->id,
                'staked_at' => now()->subHours(8),
            ]);

        $this->mock(
            PlayerRepository::class,
            fn (MockInterface $mock) => $mock
                ->shouldReceive('getIsVIP')
                ->andReturn(false)
        );

        $actualReward = GetRewardAction::execute($assets, $this->player);

        $this->assertLessThan(1, $actualReward->total->value);
    }

    /** @test */
    public function it_calculates_reward_for_one_asset()
    {
        $stakedAsset = StakedAsset::factory()
            ->withData(['population' => '1', 'schema' => AssetSchemaType::Residential, 'template_id' => 2323423])
            ->create([
                'player_id' => $this->player->id,
                'staked_at' => now()->subDay(),
            ]);

        $this->mock(
            PlayerRepository::class,
            fn (MockInterface $mock) => $mock
                ->shouldReceive('getIsVIP')
                ->andReturn(false)
        );

        $stakedAsset = new StakedAssetCollection([$stakedAsset]);

        /** @var Reward */
        $actualReward = GetRewardAction::execute($stakedAsset, $this->player);

        $expectedReward = new Reward(
            staked: new Token(1.0),
            bonus: Token::from(0.0),
            tax: Token::from(0.05),
            total: Token::from(0.95),
            stakedAssets: $stakedAsset,
            effects: collect([])
        );

        $this->assertEquals($expectedReward, $actualReward);
    }

    /** @test */
    public function test_reward_for_an_asset_with_bonus()
    {
        $this->player->is_vip = true;

        $stakedAsset = StakedAsset::factory()
            ->withData(['population' => '1', 'schema' => AssetSchemaType::Residential, 'template_id' => 2323423])
            ->create([
                'player_id' => $this->player->id,
                'staked_at' => now()->subDay(),
            ]);

        $this->mock(
            PlayerRepository::class,
            fn (MockInterface $mock) => $mock
                ->shouldReceive('getIsVIP')
                ->andReturn(true)
        );

        $stakedAsset = new StakedAssetCollection([$stakedAsset]);

        /** @var Reward */
        $actualReward = GetRewardAction::execute($stakedAsset, $this->player);

        $expectedReward = new Reward(
            staked: new Token(1.0),
            bonus: Token::from(0.5),
            tax: Token::from(0.075),
            total: Token::from(1.425),
            stakedAssets: $stakedAsset,
            effects: collect([])
        );

        $this->assertEqualsWithDelta($expectedReward, $actualReward, 0.0001);
    }

    /** @test */
    public function staked_assets_and_effects_are_empty_collections_if_they_are_not_available()
    {
        /** @var StakedAssetCollection $stakedAssets */
        $stakedAssets = StakedAsset::where('player_id', $this->player->id)->get();

        $this->mock(
            PlayerRepository::class,
            fn (MockInterface $mock) => $mock
                ->shouldReceive('getIsVIP')
                ->andReturn(false)
        );

        /** @var Reward */
        $actualReward = GetRewardAction::execute($stakedAssets, $this->player);

        $expectedReward = new Reward(
            staked: new Token(0.0),
            bonus: Token::from(0.0),
            tax: Token::from(0.0),
            total: Token::from(0.0),
            stakedAssets: $stakedAssets,
            effects: collect([])
        );

        $this->assertEquals($expectedReward, $actualReward);
    }

    /** @test */
    public function effects_collection_are_included_if_they_are_available()
    {
        StakedAsset::factory()
            ->withData([
                'templateId' => MajorPlazaEffect::TEMPLATE_ID,
                'schema' => AssetSchemaType::Special,
                'population' => 4,
            ])
            ->create([
                'player_id' => $this->player->id,
                'asset_id' => '1099712438807',
                'staked_at' => now()->subDay(),
            ]);

        $stakedAssets = StakedAsset::where('player_id', $this->player->id)->get();

        $this->mock(
            PlayerRepository::class,
            fn (MockInterface $mock) => $mock
                ->shouldReceive('getIsVIP')
                ->andReturn(false)
        );

        /** @var Reward */
        $actualReward = GetRewardAction::execute($stakedAssets, $this->player);

        $effectValue = collect([EffectValue::from(MajorPlazaEffect::NAME, 250)]);

        $this->assertEquals($effectValue, $actualReward->effects);
    }
}
