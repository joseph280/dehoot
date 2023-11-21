<?php

namespace Tests\Feature\Asset\Jobs;

use Tests\TestCase;
use Mockery\MockInterface;
use Domain\Player\Models\Player;
use App\Jobs\UnstakeTransactionJob;
use Domain\Asset\Models\StakedAsset;
use Domain\Log\Enums\TransactionType;
use Domain\Log\Models\TransactionLog;
use Domain\Asset\Actions\UnstakeAction;
use Domain\Log\Enums\TransactionStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UnstakeTransactionJobTest extends TestCase
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
    public function unstake_transaction_job_runs_with_required_params()
    {
        /** @var StakedAsset */
        $stakedAsset = StakedAsset::factory()->create([
            'player_id' => $this->player->id,
            'staked_at' => now()->subDays(3),
        ]);

        $transactionLog = TransactionLog::create([
            'player_id' => $this->player->id,
            'wallet_id' => $this->player->account_id,
            'asset_ids' => $stakedAsset->asset_id,
            'type' => TransactionType::ClaimAll,
            'status' => TransactionStatus::Processing,
        ]);

        $job = new UnstakeTransactionJob($this->player->id, $transactionLog->id, $stakedAsset->asset_id);

        $this->mock(UnstakeAction::class, function (MockInterface $mock) {
            $mock->shouldReceive('handle')->andReturn();
        });

        $job->handle();

        $this->assertClassHasAttribute('playerId', UnstakeTransactionJob::class);
        $this->assertClassHasAttribute('transactionId', UnstakeTransactionJob::class);
        $this->assertClassHasAttribute('assetId', UnstakeTransactionJob::class);
    }
}
