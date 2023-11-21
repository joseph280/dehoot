<?php

namespace Domain\EosPhp\Actions;

use Domain\Shared\Actions\Action;
use Domain\Shared\ValueObjects\Token;
use Domain\EosPhp\Interfaces\EosApiInterface;

class GetBalanceAction extends Action
{
    public function handle(string $code, string $account, string $tokenName): Token
    {
        /** @var EosApiInterface */
        $eos = (app(EosApiInterface::class));

        $balance = $eos->getBalance(
            $code,
            $account,
            $tokenName,
        );

        return Token::from($balance[0] ?? 0, $tokenName);
    }
}
