<?php

use Illuminate\Support\Facades\Route;
use Domain\Player\Controllers\AuthController;
use Domain\Player\Controllers\HomeController;
use Domain\Player\Controllers\LoginController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::post('/signin', [AuthController::class, 'login'])->name('signin');
Route::get('/login', LoginController::class)->name('login');
Route::redirect('/', '/login')->name('welcome');

Route::middleware('auth')->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
