<?php

namespace Domain\Asset\Actions;

use Domain\Shared\Actions\Action;
use Domain\Shared\ValueObjects\Token;
use Domain\Shared\ValueObjects\TaxPercentageMultiplier;

class CalculateTaxAction extends Action
{
    public function handle(Token $reward, ?TaxPercentageMultiplier $taxRate = null): Token
    {
        if (! $taxRate) {
            $taxRate = TaxPercentageMultiplier::from(5);
        }

        return Token::from($reward->value * $taxRate->formatted);
    }
}
