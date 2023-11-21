<?php

namespace Domain\Player\ValueObjects;

use Domain\Shared\ValueObjects\Token;

class PlayerBalances
{
    public function __construct(
        public Token $hootBalance,
        public Token $waxBalance
    ) {
    }

    public static function from(Token $hootBalance, Token $waxBalance): self
    {
        return new self($hootBalance, $waxBalance);
    }
}
