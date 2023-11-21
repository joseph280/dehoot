<?php

namespace Domain\EosPhp\Providers;

use Illuminate\Support\ServiceProvider;
use Domain\EosPhp\Enums\EosEnvironmentStatus;
use Domain\EosPhp\Support\EosEnvironmentManager;

class EosEnvironmentServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(EosEnvironmentManager::class, function () {
            return new EosEnvironmentManager(
                status: EosEnvironmentStatus::from(config('services.wax.env')),
            );
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
    }
}
