<?php

namespace Domain\EosPhp\Support;

use Domain\Player\Models\Player;
use Domain\EosPhp\Enums\EosEnvironmentStatus;

class EosEnvironmentManager
{
    public function __construct(
        public EosEnvironmentStatus $status
    ) {
    }

    public function getRPC(): string
    {
        return match ($this->status) {
            EosEnvironmentStatus::Production => config('services.wax.rpc'),
            EosEnvironmentStatus::Testing => config('services.wax.rpc_testnet'),
        };
    }

    public function getContractAccount(): string
    {
        return match ($this->status) {
            EosEnvironmentStatus::Production, EosEnvironmentStatus::Testing => config('services.wax.account')
        };
    }

    public function getTransferReceiverAccount(Player $player): string
    {
        return match ($this->status) {
            EosEnvironmentStatus::Production => $player->account_id,
            EosEnvironmentStatus::Testing => config('services.wax.testnet_receiver_account')
        };
    }

    public function getTaxReceiverAccount(): string
    {
        return match ($this->status) {
            EosEnvironmentStatus::Production => config('services.tax.receiver_account'),
            EosEnvironmentStatus::Testing => config('services.tax.testnet_receiver_account')
        };
    }

    public function getWaxTokenContract(): string
    {
        return match ($this->status) {
            EosEnvironmentStatus::Production, EosEnvironmentStatus::Testing => 'eosio.token',
        };
    }

    public function getMainAccount(Player $player): string
    {
        return match ($this->status) {
            EosEnvironmentStatus::Production => $player->account_id,
            EosEnvironmentStatus::Testing => config('services.wax.account'),
        };
    }
}
