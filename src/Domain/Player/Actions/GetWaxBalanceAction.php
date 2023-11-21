<?php

namespace Domain\Player\Actions;

use Domain\Player\Models\Player;
use Domain\Shared\Actions\Action;
use Domain\Player\Enums\TokenEnum;
use Domain\Shared\ValueObjects\Token;
use Domain\EosPhp\Actions\GetBalanceAction;
use Domain\EosPhp\Support\EosEnvironmentManager;

class GetWaxBalanceAction extends Action
{
    public function handle(Player $player): Token
    {
        /** @var EosEnvironmentManager */
        $eosEnv = app(EosEnvironmentManager::class);

        return GetBalanceAction::execute(
            $eosEnv->getWaxTokenContract(),
            $eosEnv->getMainAccount($player),
            (TokenEnum::Wax)->value,
        );
    }
}
