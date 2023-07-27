<?php

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
use Illuminate\Support\Facades\Route;

// Route view Email telah diverifikasi sebelumnya
Route::get('/email-verified', function () {
    return view('email-verified');
});

// Route view Email berhasil diverifikasi
Route::get('/email-verify', function () {
    return view('email-verify');
});


/*
|--------------------------------------------------------------------------
| Web Route untuk ADMIN
|--------------------------------------------------------------------------
 */

use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\Management\SopirController;
use App\Http\Controllers\Admin\Management\PenumpangController;
use App\Http\Controllers\Admin\Management\OrderController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\ProfileController;
use Illuminate\Support\Facades\Hash;


Route::get('/login', [LoginController::class, 'loginForm']);
Route::post('/login', [LoginController::class, 'loginVerify'])->name('login');
Route::get('/hashpassword', function () {
    $hashedPassword = Hash::make('12345678');
    return $hashedPassword;
});
Route::get('/', function () {
    return view('welcome');
})->middleware('auth');


Route::group(['middleware' => 'auth'], function () {
    // Route yang membutuhkan autentikasi
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    // Route Data Sopir
    Route::get('/sopir', [SopirController::class, 'index'])->name('sopir');
    Route::get('/sopir/add', [SopirController::class, 'store_view']);
    Route::post('/sopir/add', [SopirController::class, 'store'])->name('sopir.store');
    Route::get('/sopir/show/{id}', [SopirController::class, 'show_view'])->name('sopir.show');
    Route::get('/sopir/edit/{id}', [SopirController::class, 'update_view'])->name('sopir.edit');
    Route::put('/sopir/topup/{id}', [SopirController::class, 'topup'])->name('sopir.topup');
    Route::put('/sopir/ubah_saldo/{id}', [SopirController::class, 'changeSaldo'])->name('sopir.ubah_saldo');
    Route::put('/sopir/edit_driver/{id}', [SopirController::class, 'update_driver'])->name('sopir.update.driver');
    Route::put('/sopir/edit_car/{id}', [SopirController::class, 'update_car'])->name('sopir.update.car');
    Route::delete('/sopir/delete/{id}', [SopirController::class, 'destroy'])->name('sopir.destroy');

    // Route Data Penumpang
    Route::get('/penumpang', [PenumpangController::class, 'index'])->name('penumpang');
    Route::get('/penumpang/show/{id}', [PenumpangController::class, 'show_view'])->name('penumpang.show');
    Route::get('/penumpang/edit/{id}', [PenumpangController::class, 'update_view'])->name('penumpang.edit');
    Route::put('/penumpang/edit_passenger/{id}', [PenumpangController::class, 'update'])->name('penumpang.update');
    Route::delete('/penumpang/delete/{id}', [PenumpangController::class, 'destroy'])->name('penumpang.destroy');

    // Route Riwayat Pesanan
    Route::get('/pesanan', [OrderController::class, 'index'])->name('order');

    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/updatePassword', [ProfileController::class, 'updatePassword'])->name('profile.update.password');


    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});


