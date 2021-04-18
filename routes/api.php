<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\InvitationController;

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

Route::group([
    'prefix' => 'auth'
], function ($router) {
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::get('/verifyToken', [AuthController::class, 'verifyToken'])->name('verify-token');
});

Route::group([
    'middleware' => ['auth:api', 'admin'],
], function ($router) {
    Route::post('/invite', [InvitationController::class, 'index'])->name('invite');
});
