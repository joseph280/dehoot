<?php

namespace Domain\EosPhp\Enums;

enum EosEnvironmentStatus: string
{
    case Production = 'production';

    case Testing = 'testing';
}
