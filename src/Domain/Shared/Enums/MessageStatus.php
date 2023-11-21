<?php

namespace Domain\Shared\Enums;

enum MessageStatus: string
{
    case Success = 'success';

    case Information = 'information';

    case Warning = 'warning';

    case Danger = 'danger';
}
