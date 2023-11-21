<?php

namespace Domain\Shared\ValueObjects;

class TaxPercentageMultiplier
{
    public readonly ?float $value;

    public readonly float $formatted;

    public function __construct(float $value)
    {
        $this->value = $value;

        if ($value === null) {
            $this->formatted = 0;
        } else {
            $this->formatted = $value / 100;
        }
    }

    public static function from(?float $value): self
    {
        return new self($value);
    }
}
