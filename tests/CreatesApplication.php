<?php

namespace Tests;

use Tests\Mocks\AtomicBigResponse;
use Tests\Mocks\AtomicVIPResponse;
use Tests\Mocks\AtomicNoDataResponse;
use Illuminate\Foundation\Application;
use Tests\Mocks\AtomicRegularResponse;
use Illuminate\Contracts\Console\Kernel;
use Tests\Mocks\AtomicSingleAssetResponse;
use Tests\Mocks\AtomicSingleSpecialAssetResponse;
use Tests\Mocks\AtomicRegularResponseWithServices;

trait CreatesApplication
{
    /**
     * Creates the application.
     *
     * @return Application
     */
    public function createApplication(): Application
    {
        $app = require __DIR__ . '/../bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        $this->regularResponse = new AtomicRegularResponse();

        $this->noDataResponse = AtomicNoDataResponse::getResponse();
        $this->atomicResponse = AtomicRegularResponse::getResponse();
        $this->atomicBigResponse = AtomicBigResponse::getResponse();
        $this->atomicSpecialBuildResponse = AtomicVIPResponse::getResponse();
        $this->singleAssetAtomicResponse = AtomicSingleAssetResponse::getResponse();
        $this->atomicResponseWithServices = AtomicRegularResponseWithServices::getResponse();
        $this->singleSpecialAssetAtomicResponse = AtomicSingleSpecialAssetResponse::getResponse();

        return $app;
    }
}
