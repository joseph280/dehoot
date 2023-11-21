<?php

namespace Tests\Feature\Asset;

use Exception;
use Tests\TestCase;
use Mockery\MockInterface;
use Domain\Player\Models\Player;
use Illuminate\Auth\Authenticatable;
use Domain\Log\Enums\TransactionType;
use Domain\Log\Models\TransactionLog;
use Domain\Shared\ValueObjects\Token;
use Domain\Log\Enums\TransactionStatus;
use Domain\Asset\Actions\TransactAction;
use Domain\Shared\Enums\TransactTypeEnum;
use Domain\Asset\Actions\TransferTokenAction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Domain\EosPhp\Actions\CheckSuccessfulReceiptAction;
use Domain\EosPhp\Entities\Transaction\TransactionReceipt;

class TransactActionTest extends TestCase
{
    use RefreshDatabase;

    protected Player $player;

    protected Token $quantity;

    public function setUp(): void
    {
        parent::setUp();

        /** @var Player|Authenticatable */
        $this->player = Player::factory()->create();
        $this->actingAs($this->player);

        $this->quantity = Token::from(5);
    }

    /** @test */
    public function transact_is_successful_and_stores_log()
    {
        $transaction_id = 'd556e1abbe108e72d3ae2d1b0e1c9e581b95fa21931dee80e77175fd14322ffb';

        TransactionLog::create([
            'player_id' => $this->player->id,
            'wallet_id' => $this->player->account_id,
            'asset_ids' => ['1'],
            'type' => TransactionType::Unstake,
            'status' => TransactionStatus::Processing,
        ]);

        $this->mock(TransferTokenAction::class, function (MockInterface $mock) use ($transaction_id) {
            $mock->shouldReceive('handle')->andReturn(new TransactionReceipt(
                $transaction_id,
                [
                    'receipt' => [
                        'status' => 'executed',
                    ],
                ]
            ));
        });
        $this->mock(CheckSuccessfulReceiptAction::class, function (MockInterface $mock) {
            $mock->shouldReceive('handle')->andReturn(true);
        });

        /** @var TransactionLog */
        $transaction = TransactAction::execute($this->player, $this->quantity, TransactTypeEnum::Reward);

        $this->assertIsObject($transaction);
        $this->assertDatabaseHas('transaction_logs', [
            'transaction_id' => $transaction_id,
            'status' => TransactionStatus::Success->value,
        ]);
    }

    /** @test */
    public function transaction_fails_and_stores_log()
    {
        $transaction_id = 'd556e1abbe108e72d3ae2d1b0e1c9e581b95fa21931dee80e77175fd14322ffb';

        TransactionLog::create([
            'player_id' => $this->player->id,
            'wallet_id' => $this->player->account_id,
            'asset_ids' => ['1'],
            'type' => TransactionType::Unstake,
            'status' => TransactionStatus::Processing,
        ]);

        $this->mock(TransferTokenAction::class, function (MockInterface $mock) use ($transaction_id) {
            $mock->shouldReceive('handle')->andReturn(new TransactionReceipt(
                $transaction_id,
                [
                    'receipt' => [
                        'status' => 'hard_fail',
                    ],
                ]
            ));
        });
        $this->mock(CheckSuccessfulReceiptAction::class, function (MockInterface $mock) {
            $mock->shouldReceive('handle')->andReturn(false);
        });

        /** @var TransactionLog */
        $transaction = TransactAction::execute($this->player, $this->quantity, TransactTypeEnum::Reward);

        $this->assertIsObject($transaction);
        $this->assertDatabaseHas('transaction_logs', [
            'transaction_id' => $transaction_id,
            'status' => TransactionStatus::Failed->value,
        ]);
    }

    /** @test */
    public function transact_fails_and_return_exception()
    {
        $exceptionMessage = 'There was an error in request';

        $this->expectExceptionMessage($exceptionMessage);

        $this->mock(TransferTokenAction::class, function (MockInterface $mock) use ($exceptionMessage) {
            $mock->shouldReceive('handle')->andThrow(new Exception($exceptionMessage));
        });

        TransactAction::execute($this->player, $this->quantity, TransactTypeEnum::Reward);
    }
}
