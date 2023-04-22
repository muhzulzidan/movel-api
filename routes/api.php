<?php

use App\Http\Controllers\API\EmailVerificationController;
use App\Http\Controllers\API\MasterData\KotaKabController;
use App\Http\Controllers\API\PassengerController;
use App\Http\Controllers\API\PasswordResetController;
use App\Http\Controllers\API\Transaction\KotaAsalController;
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
});

// Protected Route Passengers
Route::middleware(['auth:sanctum', 'checkRole:2'])->group(function () {
    Route::get('/passenger', [PassengerController::class, 'index']);
    Route::patch('/passenger/update', [PassengerController::class, 'store']);
});

// Route Transaction (tolong jangan dihapus ya)
// ...
// Route MasterData/KotaKab
Route::get('/kota_kab', [KotaKabController::class, 'index']);

// Route Transaction/KotaAsal
Route::get('/kota_asal/search', [KotaAsalController::class, 'search_kota_asal']);
Route::get('/kota_asal/three', [KotaAsalController::class, 'three_kota_asal']);
Route::post('/kota_asal', [KotaAsalController::class, 'set_kota_asal']);
