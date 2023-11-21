<?php

namespace Domain\Shared\Traits;

use Domain\Shared\Enums\MessageStatus;

trait Redirector
{
    public function redirectByTransactionInProcessing()
    {
        return redirect(route('home'))
            ->with('message', __('messages.transaction.processing'))
            ->with('status', MessageStatus::Warning);
    }

    public function redirectByUnstakeLimitOnMajorPlaza()
    {
        return redirect(route('home'))
            ->with(['message' => __('messages.unstake.limit.major_plaza')])
            ->with(['status' => MessageStatus::Danger]);
    }

    public function redirectByClaimProcessing()
    {
        return redirect(route('home'))
            ->with('message', __('messages.claim.processing'))
            ->with('status', MessageStatus::Information);
    }
}
