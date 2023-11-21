<?php

namespace App\Exceptions;

use Throwable;
use Taecontrol\LarastatsWingman\LarastatsWingman;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        if (! app()->environment('testing')) {
            $this->reportable(function (Throwable $e) {
                /** @var LarastatsWingman $wingman */
                $wingman = app(LarastatsWingman::class);

                $wingman->captureException($e);
            });
        }
    }
}
