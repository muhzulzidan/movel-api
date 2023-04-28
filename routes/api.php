<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\Driver\DriverController;
use App\Http\Controllers\API\PasswordResetController;
use App\Http\Controllers\API\EmailVerificationController;
use App\Http\Controllers\API\MasterData\KotaKabController;
use App\Http\Controllers\API\Transaction\OrdersController;
use App\Http\Controllers\API\Passenger\PassengerController;
use App\Http\Controllers\API\MasterData\TimeDepartureController;
use App\Http\Controllers\API\Transaction\RuteScheduleController;
use App\Http\Controllers\API\Driver\RuteScheduleDriverController;
use App\Http\Controllers\API\Transaction\AvailableDriverController;

// Public Routes
Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);
Route::post('/send-reset-password-email', [PasswordResetController::class, 'send_reset_password_email']);
Route::post('/reset-password/{token}', [PasswordResetController::class, 'reset']);

// Email Verification Routes
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/email/verification-notification', [EmailVerificationController::class, 'send_verification_email'])
        ->name('verification.notice');
    Route::get('/email-verify/{id}/{hash}', [EmailVerificationController::class, 'verify'])->name('verification.verify');
});

// Protected Routes
Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::post('/logout', [UserController::class, 'logout']);
    // CRUD/Setting Profile Users
    Route::get('/read_user', [UserController::class, 'read_user']);
    Route::put('/changepassword', [UserController::class, 'change_password']);

    // Route Kota Kabupaten
    Route::get('/kota_kab/search', [KotaKabController::class, 'search_kota_kab']);

});

// Protected Route Passengers
Route::middleware(['auth:sanctum', 'checkRole:2'])->group(function () {
    //Route Profile
    Route::get('/passenger', [PassengerController::class, 'index']);
    Route::put('/passenger/update', [PassengerController::class, 'update']);

    // Route Kota Kabupaten
    Route::get('/kota_kab/three', [KotaKabController::class, 'three_kota_kab']);

    // Route Time Departure
    Route::get('/time_departure', [TimeDepartureController::class, 'index']);

    // Route Pemesanan
    Route::post('rute_jadwal/kota_asal', [RuteScheduleController::class, 'set_kota_asal']);
    Route::post('rute_jadwal/kota_tujuan', [RuteScheduleController::class, 'set_kota_tujuan']);
    Route::post('rute_jadwal/date_time', [RuteScheduleController::class, 'set_date_time']);
    Route::get('/drivers/available', [AvailableDriverController::class, 'driver_available']);
    Route::get('/drivers/available/{id}', [AvailableDriverController::class, 'driver_available_show']);
    Route::post('/drivers/available', [AvailableDriverController::class, 'set_driver_available']);
    Route::get('/drivers/list_seat_car', [AvailableDriverController::class, 'list_seat_car']);
    Route::post('/drivers/set_seat_car', [AvailableDriverController::class, 'set_seat_car']);
    Route::get('/orders/resume', [OrdersController::class, 'order_resume']);
    Route::post('/orders/set_order', [OrdersController::class, 'set_order']);
});

// Protected Route Drivers
Route::middleware(['auth:sanctum', 'verified', 'checkRole:3'])->group(function () {
    //Route Profile
    Route::get('/driver', [DriverController::class, 'index']);
    Route::put('/driver/update', [DriverController::class, 'update']);

    Route::match (['post', 'patch'], '/drivers/rute_jadwal', [RuteScheduleDriverController::class, 'store_update_rute_jadwal']);
});
