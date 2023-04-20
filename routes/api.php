<?php

use App\Http\Controllers\API\PassengerController;
use App\Http\Controllers\API\PasswordResetController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\VerificationController;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);
Route::post('/send-reset-password-email', [PasswordResetController::class, 'send_reset_password_email']);
Route::post('/reset-password/{token}', [PasswordResetController::class, 'reset']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/send-verification-email', [VerificationController::class, 'send_verification_email']);
    Route::get('/verification-email/{id}/{hash}', [VerificationController::class, 'verification_email'])
                ->middleware('signed');
    Route::post('/resend-verification-email', [VerificationController::class, 'resend_verification_email'])
                ->middleware('throttle:6,1');
});

// Protected Routes
Route::middleware(['auth:sanctum'])->group(function () {
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
