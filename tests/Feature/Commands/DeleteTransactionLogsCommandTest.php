<?php

namespace Tests\Feature\Commands;

use Tests\TestCase;
use Domain\Player\Models\Player;
use Illuminate\Auth\Authenticatable;
use Domain\Log\Models\TransactionLog;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Console\Commands\DeleteTransactionLogsCommand;
use Illuminate\Console\Scheduling\Event as ScheduleEvent;

class DeleteTransactionLogsCommandTest extends TestCase
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
    public function it_deletes_transaction_logs_older_than_a_month()
    {
        TransactionLog::factory(2)->success()->create([
            'player_id' => $this->player->id,
            'created_at' => now()->subYear(),
        ]);

        TransactionLog::factory()->success()->create([
            'player_id' => $this->player->id,
            'created_at' => now(),
        ]);

        $command = new DeleteTransactionLogsCommand();

        $command->handle();

        $this->assertDatabaseCount('transaction_logs', 1);
    }

     /** @test */
     public function it_does_not_deletes_failed_transactions()
     {
         TransactionLog::factory(2)->failed()->create([
             'player_id' => $this->player->id,
             'created_at' => now()->subYear(),
         ]);

         $command = new DeleteTransactionLogsCommand();

         $command->handle();

         $this->assertDatabaseCount('transaction_logs', 2);
     }

    /** @test */
    public function delete_transaction_logs_command_is_scheduled()
    {
        $schedule = $this->app->get(Schedule::class);

        $commandIsScheduled = collect($schedule->events())
            ->contains(
                fn (ScheduleEvent $event) => str_contains(
                    $event->command,
                    DeleteTransactionLogsCommand::getDefaultName()
                )
            );

        $this->asserttrue($commandIsScheduled);
    }
}
