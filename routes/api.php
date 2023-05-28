<?php

use App\Http\Controllers\API\Admin\AllDriversController;
use App\Http\Controllers\API\Driver\DriverController;
use App\Http\Controllers\API\Driver\DriverDepartureController;
use App\Http\Controllers\API\Driver\DriverOrderController;
use App\Http\Controllers\API\MasterData\CarController;
use App\Http\Controllers\API\MasterData\KotaKabController;
use App\Http\Controllers\API\MasterData\TimeDepartureController;
use App\Http\Controllers\API\Passenger\PassengerController;
use App\Http\Controllers\API\Passenger\PassengerOrderController;
use App\Http\Controllers\API\Transaction\OrderController;
use App\Http\Controllers\API\Transaction\RatingController;
use App\Http\Controllers\API\UserController;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::post('/register', [UserController::class, 'registerPassenger']);
// Route verifikasi email
Route::get('/email/verify/{id}', [UserController::class, 'verify'])->name('verification.verify');
// Route login ke sistem (Email harus terverifikasi)
Route::post('/login', [UserController::class, 'login']);
//Route Forget and Reset Password
Route::post('/forgot_password', [UserController::class, 'forgetPassword']);
Route::post('/reset_password', [UserController::class, 'reset']);

// Group Middleware (telah login dan email terverifikasi)
Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    //Route untuk logout/keluar dari sistem
    Route::post('/logout', [UserController::class, 'logout']);
    // CRUD/Setting Profile Users
    Route::get('/read_user', [UserController::class, 'read_user']);
    // Route ganti password
    Route::put('/change_password', [UserController::class, 'changePassword']);
    // Route Kota Kabupaten
    Route::get('/kota_kab/search', [KotaKabController::class, 'search_kota_kab']);
    // Route Ganti Foto Profil
    Route::post('/change_foto_profil', [UserController::class, 'changeFotoProfil']);
});

// Protected Route Passengers
Route::middleware(['auth:sanctum', 'verified', 'checkRole:2'])->group(function () {
    //Route Booking (Terbaru)
    Route::post('drivers/available', [OrderController::class, 'getDriverAvailable']);
    Route::get('drivers/available/{id}', [OrderController::class, 'getDriverAvailableById']);
    Route::get('/drivers/available/{id}/seat_cars', [OrderController::class, 'getSeatCars']);
    Route::post('/orders/resume', [OrderController::class, 'getOrderResume']);
    Route::post('/orders', [OrderController::class, 'storeOrder']);
    //Route Profile
    Route::get('/passenger', [PassengerController::class, 'index']);
    Route::put('/passenger/update', [PassengerController::class, 'update']);
    // Route Kota Kabupaten
    Route::get('/kota_kab/three', [KotaKabController::class, 'three_kota_kab']);
    // Route Time Departure
    Route::get('/time_departure', [TimeDepartureController::class, 'index']);

    Route::get('/ratings/driver', [RatingController::class, 'showDriverForRating']);
    Route::post('/ratings', [RatingController::class, 'addRating']);

    Route::put('/orders/passenger/cancel', [PassengerOrderController::class, 'setCancelPassengerOrder']);
    Route::get('/orders/passenger/status', [PassengerOrderController::class, 'passengerOrderStatus']);
    Route::get('orders/passenger', [PassengerOrderController::class, 'showListPassengerOrder']);
    Route::get('orders/{id}/passenger', [PassengerOrderController::class, 'detailPassengerOrder']);
});

// Protected Route Drivers
Route::middleware(['auth:sanctum', 'verified', 'checkRole:3'])->group(function () {
    //Route Profile
    Route::get('/driver', [DriverController::class, 'index']);
    Route::put('/driver/update', [DriverController::class, 'update']);

    Route::match (['post', 'put'], '/drivers/rute_jadwal', [DriverDepartureController::class, 'storeUpdateRuteJadwal']);
    Route::put('/drivers/inactive', [DriverDepartureController::class, 'setDriverInactive']);
    Route::get('drivers/seat_cars', [DriverDepartureController::class, 'getListSeatCars']);

    Route::get('/orders/driver', [DriverOrderController::class, 'showOrdersByDriver']);
    Route::get('/orders/{id}/driver', [DriverOrderController::class, 'showDriverOrderById']);
    Route::put('/orders/{id}/driver/accept', [DriverOrderController::class, 'updateDriverOrderAccept']);
    Route::put('/orders/{id}/driver/reject', [DriverOrderController::class, 'updateDriverOrderReject']);
    Route::get('/orders/driver/accepted', [DriverOrderController::class, 'showDriverOrderAccepted']);
    Route::get('/orders/driver/rejected', [DriverOrderController::class, 'showDriverOrderRejected']);
    Route::get('orders/{id}/driver/rejected', [DriverOrderController::class, 'detailRejectedDriverOrder']);
    Route::get('orders/driver/completed', [DriverOrderController::class, 'showListCompletedDriverOrders']);
    Route::get('orders/{id}/driver/completed', [DriverOrderController::class, 'detailCompletedDriverOrder']);
    Route::post('orders/driver/take_self', [DriverOrderController::class, 'addPassengerByDriver']);

    Route::put('/orders/{id}/pick_location', [OrderController::class, 'updateOrderPickLocation']);
    Route::put('/orders/{id}/pick_location_arrive', [OrderController::class, 'updateOrderPickLocationArrive']);
    Route::put('/orders/{id}/complete', [OrderController::class, 'updateOrderComplete']);
    Route::put('/orders/{id}/cancel_accept', [OrderController::class, 'updateOrderCancelled']);
    Route::put('/orders/{id}/cancel_reject', [OrderController::class, 'updateOrderNotCancelled']);

    Route::get('/cars/seat_car', [CarController::class, 'getSeatCar']);
    Route::get('/cars', [CarController::class, 'getCar']);
});

// Protected Route Admin
Route::middleware(['auth:sanctum', 'verified', 'checkRole:1'])->group(function () {
    //Route Profile
    Route::get('/all_drivers', [AllDriversController::class, 'index']);
    Route::get('/all_drivers/{id}', [AllDriversController::class, 'show']);
    Route::post('/driver/store', [AllDriversController::class, 'store']);
    Route::put('/driver/update/{id}', [AllDriversController::class, 'update']);
});
