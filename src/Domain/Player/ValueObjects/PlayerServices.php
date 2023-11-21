<?php

namespace Domain\Player\ValueObjects;

use Domain\Asset\ValueObjects\ServiceConsumption;

class PlayerServices
{
    public function __construct(
        public ServiceConsumption $water,
        public ServiceConsumption $energy
    ) {
    }

    public static function from(ServiceConsumption $water, ServiceConsumption $energy): self
    {
        return new self($water, $energy);
    }
}
