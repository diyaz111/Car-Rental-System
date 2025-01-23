<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CreateBookController;
use App\Http\Controllers\Api\DataCarsContoller;
use App\Http\Controllers\Api\LoginApiController;
use Illuminate\Support\Facades\Route;

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

Route::post('login', [AuthController::class, 'login']);
Route::middleware('auth.jwt')->group(function () {
    Route::post('/booking/create', [CreateBookController::class, 'create']);
    Route::post('/cars/search', [DataCarsContoller::class, 'search']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('me', [AuthController::class, 'me']);
});

