<?php

namespace Domain\Asset\ValueObjects;

class Position
{
    public function __construct(
        public int $x,
        public int $y
    ) {
    }

    public static function from(int $x, int $y): self
    {
        return new self($x, $y);
    }
}
