<?php

namespace Tests\Feature\Asset\Jobs;

use Tests\TestCase;
use Mockery\MockInterface;
use Domain\Player\Models\Player;
use Illuminate\Support\Collection;
use Domain\Asset\Models\StakedAsset;
use Domain\Log\Enums\TransactionType;
use Domain\Log\Models\TransactionLog;
use Domain\Log\Enums\TransactionStatus;
use Domain\Asset\Actions\ClaimAllAction;
use Domain\Asset\Jobs\ClaimAllTransactionJob;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ClaimAllTransactionJobTest extends TestCase
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
    public function claim_all_transaction_job_runs_with_required_params()
    {
        /** @var Collection */
        $stakedAssets = StakedAsset::factory(3)->create([
            'player_id' => $this->player->id,
            'staked_at' => now()->subDays(3),
        ]);

        $transactionLog = TransactionLog::create([
            'player_id' => $this->player->id,
            'wallet_id' => $this->player->account_id,
            'asset_ids' => $stakedAssets->pluck('asset_id'),
            'type' => TransactionType::ClaimAll,
            'status' => TransactionStatus::Processing,
        ]);

        $job = new ClaimAllTransactionJob($this->player->id, $transactionLog->id);

        $this->mock(ClaimAllAction::class, function (MockInterface $mock) {
            $mock->shouldReceive('handle')->andReturn();
        });

        $job->handle();

        $this->assertClassHasAttribute('playerId', ClaimAllTransactionJob::class);
        $this->assertClassHasAttribute('transactionId', ClaimAllTransactionJob::class);
    }
}
