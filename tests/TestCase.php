<?php

namespace Tests;

use Tests\Mocks\AtomicRegularResponse;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected AtomicRegularResponse $regularResponse;

    protected array $noDataResponse;

    protected array $atomicResponse;

    protected array $atomicBigResponse;

    protected array $atomicSpecialBuildResponse;

    protected array $singleAssetAtomicResponse;

    protected array $singleSpecialAssetAtomicResponse;

    protected array $atomicResponseWithServices;
}
