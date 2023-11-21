<?php

namespace Domain\Asset\Actions;

use Carbon\Carbon;
use Domain\Shared\Actions\Action;

class GetTimeDurationInDaysAction extends Action
{
    public function handle(Carbon $from, Carbon $to): int
    {
        $durationInDays = $to->diffInDays($from);

        return $durationInDays;
    }
}
