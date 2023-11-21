<?php

namespace Tests\Feature\Asset;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Domain\Asset\Actions\GetTimeDurationInDaysAction;

class GetTimeDurationInDaysActionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function getting_time_duration_of_many_days()
    {
        $date_from = now()->subDays(4);
        $amount = GetTimeDurationInDaysAction::execute($date_from, now());

        $this->assertGreaterThan(1, $amount);
    }

    /** @test */
    public function getting_no_time_duration_in_days()
    {
        $date_from = now();
        $amount = GetTimeDurationInDaysAction::execute($date_from, now());

        $this->assertLessThan(1, $amount);
    }
}
