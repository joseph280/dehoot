<?php

namespace Domain\Atomic\Interfaces;

use Domain\Player\Models\Player;

interface AtomicApiManagerInterface
{
    public function asset(Player $player, string $templateId): array | null;

    public function assets(Player $player): array | null;

    public function specialAssets(Player $player): array | null;
}
