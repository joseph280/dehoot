<?php

namespace Domain\Asset\Entities;

use Domain\Asset\Effects\TheHutEffect;
use Domain\Asset\Effects\MajorPlazaEffect;
use Domain\Asset\Effects\FirstHootBankEffect;

class SpecialBuild extends Asset
{
    public const SCHEMA_NAME = 'specialbuild';

    public function effect(): mixed
    {
        $classname = match ($this->templateId) {
            MajorPlazaEffect::TEMPLATE_ID => MajorPlazaEffect::class,
            FirstHootBankEffect::TEMPLATE_ID => FirstHootBankEffect::class,
            TheHutEffect::TEMPLATE_ID => TheHutEffect::class,
            default => null,
        };

        return $classname ? new $classname($this) : null;
    }
}
