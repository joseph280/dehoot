<?php

namespace Tests\Feature\Asset;

use Tests\TestCase;
use Mockery\MockInterface;
use Domain\Player\Models\Player;
use Illuminate\Auth\Authenticatable;
use Domain\Shared\ValueObjects\Token;
use Domain\Log\ValueObjects\ActionData;
use Domain\Shared\Enums\TransactTypeEnum;
use Domain\Log\Enums\TransactionActionName;
use Domain\Asset\Actions\TransferTokenAction;
use Domain\EosPhp\Interfaces\EosApiInterface;
use Domain\EosPhp\Support\EosEnvironmentManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Domain\EosPhp\Entities\Transaction\TransactionReceipt;

class TransferTokenActionTest extends TestCase
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

        $this->quantity = Token::from(10);
    }

    /** @test */
    public function transfer_token_reward_quantity()
    {
        $this->mock(EosApiInterface::class, function (MockInterface $mock) {
            $mock->shouldReceive('transact')->andReturn(new TransactionReceipt(
                'd556e1abbe108e72d3ae2d1b0e1c9e581b95fa21931dee80e77175fd14322ffb',
                [
                    'receipt' => [
                        'status' => 'executed',
                    ],
                ]
            ));
        });

        /** @var EosEnvironmentManager */
        $eosEnv = app(EosEnvironmentManager::class);

        $actionData = new ActionData(
            from: $eosEnv->getContractAccount(),
            memo: TransactionActionName::Transfer->value,
            quantity: $this->quantity->formattedWithToken,
            to: $eosEnv->getTransferReceiverAccount($this->player),
        );

        $transference = TransferTokenAction::execute($actionData, TransactTypeEnum::Reward);

        $this->assertIsObject($transference);
    }
}
