<?php

namespace Domain\EosPhp\Providers;

use Domain\EosPhp\Api\EosApi;
use Domain\EosPhp\Api\JsonRpc;
use Illuminate\Support\ServiceProvider;
use Domain\EosPhp\Interfaces\EosApiInterface;
use Domain\EosPhp\Support\EosEnvironmentManager;

class EosPhpServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(EosApiInterface::class, function () {
            $eosEnvManager = app(EosEnvironmentManager::class);

            $rpc = new JsonRpc(
                baseUrl: $eosEnvManager->getRPC(),
            );

            $privateKeys = explode(',', config('services.wax.private_keys'));

            return new EosApi($rpc, $privateKeys);
        });
    }
}
