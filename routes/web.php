<?php

use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| This controller handles the homepage and other public-facing pages that don't require authentication
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\HomeController;

Route::get('/', [HomeController::class, 'index'])->name('welcome');


/*
|--------------------------------------------------------------------------
| This controller handles Login Logic
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\Auth\LoginController;

Route::get('login', [LoginController::class, 'showLoginForm'])->name('login.form');
Route::post('login', [LoginController::class, 'processLogin'])->name('login.submit');
Route::get('logout', [LoginController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| This controller handles Admin Logic
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\Admin\AdminController;

Route::get('admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

/*
|--------------------------------------------------------------------------
| This controller handles Client Logic
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\Client\ClientController;

Route::get('client/dashboard', [ClientController::class, 'index'])->name('client.dashboard');
