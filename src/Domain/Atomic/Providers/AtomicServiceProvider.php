<?php

namespace Domain\Atomic\Providers;

use Domain\Atomic\Api\AtomicApiManager;
use Illuminate\Support\ServiceProvider;
use Domain\Atomic\Interfaces\AtomicApiManagerInterface;

class AtomicServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(AtomicApiManagerInterface::class, function () {
            return new AtomicApiManager(
                config('services.atomic.api_url'),
                config('services.atomic.collection_name'),
                config('services.atomic.schema_names'),
            );
        });
    }
}
