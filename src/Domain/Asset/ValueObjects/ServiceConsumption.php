<?php

namespace Domain\Asset\ValueObjects;

use Webmozart\Assert\InvalidArgumentException;

class ServiceConsumption
{
    public readonly string $total;

    public readonly int $current;

    public readonly string $percentage;

    public function __construct(float $current, float $total)
    {
        if ($current < 0) {
            throw new InvalidArgumentException('Current consumption cant be negative value');
        }

        if ($total < 0) {
            throw new InvalidArgumentException('Current total cant be negative value');
        }

        $this->current = $current;
        $this->total = $total;

        if ($total > 0) {
            $this->percentage = number_format(($current * 100) / $total, 2) . '%';
        } else {
            $this->percentage = '0%';
        }
    }

    public static function from(float $current, float $total): self
    {
        return new self($current, $total);
    }
}
