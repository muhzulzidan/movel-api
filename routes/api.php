<?php

use App\Http\Controllers\API\Admin\AllDriversController;
use App\Http\Controllers\API\Driver\DriverController;
use App\Http\Controllers\API\Driver\NewOrderController;
use App\Http\Controllers\API\Driver\OrderDriverController;
use App\Http\Controllers\API\Driver\RuteScheduleDriverController;
use App\Http\Controllers\API\MasterData\KotaKabController;
use App\Http\Controllers\API\MasterData\TimeDepartureController;
use App\Http\Controllers\API\Passenger\OrderPassengerController;
use App\Http\Controllers\API\Passenger\PassengerController;
use App\Http\Controllers\API\Transaction\AvailableDriverController;
use App\Http\Controllers\API\Transaction\OrderController;
use App\Http\Controllers\API\Transaction\RatingController;
use App\Http\Controllers\API\Transaction\RuteScheduleController;
use App\Http\Controllers\API\UserController;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::post('/register', [UserController::class, 'registerPassenger']);
// Route verifikasi email
Route::get('/email/verify/{id}', [UserController::class, 'verify'])->name('verification.verify');
// Route login ke sistem (Email harus terverifikasi)
Route::post('/login', [UserController::class, 'login']);

// Group Middleware (telah login dan email terverifikasi)
Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    //Route untuk logout/keluar dari sistem
    Route::post('/logout', [UserController::class, 'logout']);

    // CRUD/Setting Profile Users
    Route::get('/read_user', [UserController::class, 'read_user']);
    Route::put('/changepassword', [UserController::class, 'change_password']);

    // Route Kota Kabupaten
    Route::get('/kota_kab/search', [KotaKabController::class, 'search_kota_kab']);

});

// Protected Route Passengers
Route::middleware(['auth:sanctum', 'verified', 'checkRole:2'])->group(function () {
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
    Route::get('/orders/resume', [OrderController::class, 'order_resume']);
    Route::post('/orders/set_order', [OrderController::class, 'set_order']);
    Route::get('/orders/status', [OrderController::class, 'orderStatus']);
    Route::get('/ratings/driver', [RatingController::class, 'showDriverForRating']);
    Route::post('/ratings', [RatingController::class, 'addRating']);
    Route::get('orders/passenger', [OrderPassengerController::class, 'showListOrderPassenger']);
    Route::get('orders/{id}/passenger', [OrderPassengerController::class, 'detailOrderPassenger']);
});

// Protected Route Drivers
Route::middleware(['auth:sanctum', 'verified', 'checkRole:3'])->group(function () {
    //Route Profile
    Route::get('/driver', [DriverController::class, 'index']);
    Route::put('/driver/update', [DriverController::class, 'update']);
    Route::match (['post', 'put'], '/drivers/rute_jadwal', [RuteScheduleDriverController::class, 'store_update_rute_jadwal']);
    Route::get('/orders/driver', [NewOrderController::class, 'showOrdersByDriver']);
    Route::get('/orders/accepted', [OrderController::class, 'showOrderAccepted']);
    Route::get('/orders/{id}', [NewOrderController::class, 'showOrderById']);
    Route::put('/orders/{id}/accept', [NewOrderController::class, 'updateOrderAccept']);
    Route::put('/orders/{id}/reject', [NewOrderController::class, 'updateOrderReject']);
    Route::put('/orders/{id}/pick_location', [OrderController::class, 'updateOrderPickLocation']);
    Route::put('/orders/{id}/pick_location_arrive', [OrderController::class, 'updateOrderPickLocationArrive']);
    Route::put('/orders/{id}/complete', [OrderController::class, 'updateOrderComplete']);
    Route::get('orders/driver/complete', [OrderDriverController::class, 'showListOrderDriver']);
    Route::get('orders/{id}/driver/complete', [OrderDriverController::class, 'detailOrderDriver']);
});

// Protected Route Admin
Route::middleware(['auth:sanctum', 'verified', 'checkRole:1'])->group(function () {
    //Route Profile
    Route::get('/all_drivers', [AllDriversController::class, 'index']);
    Route::get('/all_drivers/{id}', [AllDriversController::class, 'show']);
    Route::post('/driver/store', [AllDriversController::class, 'store']);
    Route::put('/driver/update/{id}', [AllDriversController::class, 'update']);
});
