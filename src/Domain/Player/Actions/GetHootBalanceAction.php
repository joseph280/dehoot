<?php

namespace Domain\Player\Actions;

use Domain\Player\Models\Player;
use Domain\Shared\Actions\Action;
use Domain\Shared\ValueObjects\Token;
use Domain\EosPhp\Actions\GetBalanceAction;
use Domain\EosPhp\Support\EosEnvironmentManager;

class GetHootBalanceAction extends Action
{
    public function handle(Player $player): Token
    {
        /** @var EosEnvironmentManager */
        $eosEnv = (app(EosEnvironmentManager::class));

        return GetBalanceAction::execute(
            $eosEnv->getContractAccount(),
            $eosEnv->getTransferReceiverAccount($player),
            config('services.token.name')
        );
    }
}
