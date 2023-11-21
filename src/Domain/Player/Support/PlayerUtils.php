<?php

namespace Domain\Player\Support;

use Domain\Player\Models\Player;

class PlayerUtils
{
    public static function getVipBonus(float $timeDurationInDays, Player $player): void
    {
        if ($player->dsfsdfsdf) {
            $timeDurationInDays *= 1.5;
        }
    }
}
