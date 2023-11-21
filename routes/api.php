<?php

use Illuminate\Support\Facades\Route;
use Domain\Asset\Controllers\Api\StakeController;
use Domain\Asset\Controllers\Api\UnstakeController;
use Domain\Asset\Controllers\Api\ClaimAllController;
use Domain\Player\Controllers\Api\PlayerStatsController;
use Domain\Asset\Controllers\Api\GetPlayerAssetsController;
use Domain\Player\Controllers\Api\GetPlayerRewardController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->prefix('v1')->group(function () {
    Route::get('/player/stats', PlayerStatsController::class)->name('player.stats');
    Route::get('/player/assets', GetPlayerAssetsController::class)->name('player.assets');
    Route::get('/player/reward', GetPlayerRewardController::class)->name('player.reward');

    Route::post('/stake', StakeController::class)->name('assets.stake');
    Route::post('/unstake', UnstakeController::class)->name('assets.unstake');
    Route::post('/claim-all', ClaimAllController::class)->name('assets.claim_all');
});
