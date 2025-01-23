<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingCreateController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Tymon\JWTAuth\Facades\JWTAuth;

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

Route::get('login', function () {
    return view('auth.login');
})->name('login');

Route::get('/', function () {
    return view('auth.login');
})->name('login');
Route::post('login', [AuthController::class, 'login']);

Route::get('logout', [AuthController::class, 'logout'])->name('logout');


Route::middleware('auth')->group(function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('booking/create', [BookingCreateController::class, 'create'])->name('booking.create');
    Route::get('cars/datatable', [App\Http\Controllers\HomeController::class, 'datatable'])->name('datatable');
    Route::post('/booking/store', [BookingCreateController::class, 'store'])->name('booking.store');
    Route::get('/booking/details', [BookingCreateController::class, 'showBookingDetails'])->name('booking.details');

    Route::get('/booking', [BookingCreateController::class, 'index'])->name('booking');

    // Admin-specific routes with additional role check
    Route::middleware('role:admin')->group(function () {
        Route::get('booking/datatable', [BookingCreateController::class, 'datatableAdmin'])->name('datatableAdmin');
        Route::patch('/booking/{id}/update-status', [BookingCreateController::class, 'updateStatus'])->name('booking.update-status');
    });
});
