<?php

namespace Tests\Feature\Asset\Controllers\Api;

use Tests\TestCase;
use Domain\Player\Models\Player;
use Domain\Asset\Models\StakedAsset;
use Domain\Log\Models\TransactionLog;
use Illuminate\Support\Facades\Queue;
use Domain\Shared\Enums\MessageStatus;
use Domain\Asset\Jobs\ClaimAllTransactionJob;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ClaimAllControllerTest extends TestCase
{
    use RefreshDatabase;

    protected Player $player;

    public function setUp(): void
    {
        parent::setUp();

        /** @var Player */
        $this->player = Player::factory()->create();
    }

    /** @test */
    public function unauthorized_user_cant_claim_all_the_assets()
    {
        $this->postJson(route('api.assets.claim_all'))
            ->assertUnauthorized();
    }

    /** @test */
    public function it_dispatches_claim_all_transaction_job_when_unstaking()
    {
        Queue::fake();

        $this->actingAs($this->player);

        StakedAsset::factory(3)->create([
            'player_id' => $this->player->id,
            'staked_at' => now()->subDays(3),
        ]);

        $this->postJson(route('api.assets.claim_all'))
            ->assertOk();

        Queue::assertPushed(ClaimAllTransactionJob::class);
    }

    /** @test */
    public function cant_claim_all_if_another_transaction_is_being_processed()
    {
        Queue::fake();

        $this->actingAs($this->player);

        TransactionLog::factory()
            ->processing()
            ->for($this->player)
            ->create();

        $this->postJson(route('api.assets.claim_all'))
            ->assertUnprocessable()
            ->assertJson(
                fn (AssertableJson $json) => $json
                    ->where('success', false)
                    ->has('flash')
                    ->where('flash.status', MessageStatus::Danger->value)
                    ->where('flash.message', __('messages.transaction.already_processing'))
            );

        Queue::assertNothingPushed();
    }
}
