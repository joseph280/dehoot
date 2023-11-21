<?php

namespace Domain\Asset\ValueObjects;

use Domain\Shared\ValueObjects\Token;

class EffectValue
{
    public readonly string $name;

    public Token $bonus;

    public function __construct(string $name, float $bonus)
    {
        $this->name = $name;
        $this->bonus = Token::from($bonus);
    }

    public static function from(string $name, float $bonus): self
    {
        return new self($name, $bonus);
    }
}
