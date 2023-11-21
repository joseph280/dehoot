<?php

namespace Tests\Feature\Asset;

use Tests\TestCase;
use Domain\Asset\Models\StakedAsset;
use Domain\Shared\ValueObjects\Token;
use Domain\Asset\Actions\CheckRewardAction;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CheckRewardActionTest extends TestCase
{
    use RefreshDatabase;

    protected StakedAsset $stakedAsset;

    public function setUp(): void
    {
        parent::setUp();

        $this->stakedAsset = StakedAsset::factory()->create();
    }

    /** @test */
    public function checking_has_reward_amount()
    {
        $this->stakedAsset->staked_at = now()->subDays(3);

        $hasReward = CheckRewardAction::execute($this->stakedAsset, Token::from(3));

        $this->assertTrue($hasReward);
    }

    /** @test */
    public function checking_has_not_reward_amount()
    {
        $this->stakedAsset->staked_at = now();

        $hasReward = CheckRewardAction::execute($this->stakedAsset, Token::from(0));

        $this->assertFalse($hasReward);
    }

    /** @test */
    public function checking_that_staked_at_timestamp_must_be_a_past_date()
    {
        $this->stakedAsset->staked_at = now();

        $hasReward = CheckRewardAction::execute($this->stakedAsset, Token::from(4));

        $this->assertFalse($hasReward);
    }

    /** @test */
    public function checking_that_reward_must_have_amount_bigger_to_zero()
    {
        $this->stakedAsset->staked_at = now()->subDays(3);

        $hasReward = CheckRewardAction::execute($this->stakedAsset, Token::from(0));

        $this->assertFalse($hasReward);
    }
}
