<?php

namespace Tests\Feature\Asset;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Domain\EosPhp\Actions\CheckSuccessfulReceiptAction;
use Domain\EosPhp\Entities\Transaction\TransactionReceipt;

class CheckSuccessfulReceiptActionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function receipt_is_successful()
    {
        $receipt = new TransactionReceipt(
            'd556e1abbe108e72d3ae2d1b0e1c9e581b95fa21931dee80e77175fd14322ffb',
            [
                'receipt' => [
                    'status' => 'executed',
                ],
            ]
        );

        $receiptIsSuccessful = CheckSuccessfulReceiptAction::execute($receipt);

        $this->assertTrue($receiptIsSuccessful);
    }

    /** @test */
    public function receipt_is_not_successful()
    {
        $receipt = new TransactionReceipt(
            'd556e1abbe108e72d3ae2d1b0e1c9e581b95fa21931dee80e77175fd14322ffb',
            [
                'receipt' => [
                    'status' => 'hard_fail',
                ],
            ]
        );

        $receiptIsSuccessful = CheckSuccessfulReceiptAction::execute($receipt);

        $this->assertFalse($receiptIsSuccessful);
    }
}
