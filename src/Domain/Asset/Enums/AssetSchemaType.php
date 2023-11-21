<?php

namespace Domain\Asset\Enums;

enum AssetSchemaType: string
{
    case Residential = 'residential';

    case Special = 'specialbuild';

    case Service = 'service';
}
