<?php

namespace Domain\Asset\ValueObjects;

class Dimension
{
    public function __construct(
        public int $rows,
        public int $columns
    ) {
    }

    public static function from(int $rows, int $columns): self
    {
        return new self($rows, $columns);
    }
}
