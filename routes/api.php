<?php

use App\Http\Controllers\API\Driver\RuteScheduleDriverController;
use App\Http\Controllers\API\EmailVerificationController;
use App\Http\Controllers\API\MasterData\KotaKabController;
use App\Http\Controllers\API\PassengerController;
use App\Http\Controllers\API\PasswordResetController;
use App\Http\Controllers\API\Transaction\DriverDepartureController;
use App\Http\Controllers\API\Transaction\JadwalBerangkatController;
use App\Http\Controllers\API\Transaction\KotaAsalController;
use App\Http\Controllers\API\Transaction\KotaTujuanController;
use App\Http\Controllers\API\UserController;
use Illuminate\Support\Facades\Route;

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
    // Route::post('/resend-verification-email', [EmailVerificationController::class, 'resend_verification_email'])
    //             ->middleware('throttle:6,1')->name('verification.send');
});

// Protected Routes
Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::post('/logout', [UserController::class, 'logout']);
    Route::get('/loggeduser', [UserController::class, 'logged_user']);
    Route::patch('/changepassword', [UserController::class, 'change_password']);
    Route::get('/passenger', [PassengerController::class, 'index']);

    // Route Kota Kabupaten
    Route::get('/kota_kab/search', [KotaKabController::class, 'search_kota_kab']);

});

// Protected Route Passengers
Route::middleware(['auth:sanctum', 'checkRole:2'])->group(function () {
    Route::get('/passenger', [PassengerController::class, 'index']);
    Route::patch('/passenger/update', [PassengerController::class, 'store']);
});

// Protected Route Drivers
Route::middleware(['auth:sanctum', 'verified', 'checkRole:3'])->group(function () {
    Route::match(['post', 'patch'], '/drivers/rute_jadwal', [RuteScheduleDriverController::class, 'store_update_rute_jadwal']);
});

// Route Transaction (tolong jangan dihapus ya)
// ...

// Route Passenger
// Route Transaction/KotaAsal
Route::get('/kota_asal_pass/search', [KotaAsalController::class, 'search_kota_asal_pass']);
Route::get('/kota_asal_pass/three', [KotaAsalController::class, 'three_kota_asal_pass']);
Route::post('/kota_asal_pass', [KotaAsalController::class, 'set_kota_asal_pass']);

// Route Transaction/KotaTujuan
Route::get('/kota_tujuan_pass/search', [KotaTujuanController::class, 'search_kota_tujuan_pass']);
Route::get('/kota_tujuan_pass/three', [KotaTujuanController::class, 'three_kota_tujuan_pass']);
Route::post('/kota_tujuan_pass', [KotaTujuanController::class, 'set_kota_tujuan_pass']);

// Route Transaction/JadwalBerangkat
Route::post('/jadwal_berangkat_pass', [JadwalBerangkatController::class, 'set_jadwal_berangkat_pass']);

// Route Driver
// Route Rute dan Jadwal Driver

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    // Route::post('/drivers/rute_jadwal', [RuteScheduleDriverController::class, 'store_rute_jadwal']);
    // Route::patch('/drivers/rute_jadwal', [RuteScheduleDriverController::class, 'update_rute_jadwal']);
});

// Route Transaction/DriverDeparture
Route::get('/drivers/available', [DriverDepartureController::class, 'driver_available']);
