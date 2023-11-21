<?php

namespace Domain\Asset\Effects;

use Domain\Player\Models\Player;
use Illuminate\Support\Collection;
use Domain\Shared\ValueObjects\Token;
use Domain\Player\Actions\GetHootBalanceAction;
use Domain\Asset\Actions\GetTimeDurationInDaysAction;

class FirstHootBankEffect
{
    public const TEMPLATE_ID = '431347';

    public const NAME = 'First Hoot Bank';

    private const BANK_MULTIPLIER = 0.01;

    private const BANK_LIMIT = 50000.0;

    public static function effect(Collection $banks, Player $player): array
    {
        $bonus = 0;

        $banks = $banks->where('staked_at', '<=', now()->subDay());

        if ($banks->isNotEmpty()) {
            /** @var Token */
            $totalBalance = GetHootBalanceAction::execute($player);
        }

        $banks->each(function ($bank, $index) use (&$totalBalance, &$bonus) {
            $bankDays = GetTimeDurationInDaysAction::execute($bank->staked_at, now());

            $maxBalance = ($index + 1) * self::BANK_LIMIT;

            if ($totalBalance->value >= $maxBalance) {
                $bonus += 500 * $bankDays;
            }

            if ($totalBalance->value < $maxBalance) {
                $bonus = $totalBalance->value * self::BANK_MULTIPLIER * $bankDays;

                return false;
            }
        });

        return [
            'bonus' => $bonus,
            'assets' => $banks,
        ];
    }

    public static function isFirstHootBank(string $templateId)
    {
        return $templateId === self::TEMPLATE_ID;
    }
}
