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
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\ProfileController;


Route::get('/login', [LoginController::class, 'loginForm']);
Route::post('/login', [LoginController::class, 'loginVerify'])->name('login');
Route::get('/', function () {
    return view('welcome');
})->middleware('auth');

Route::group(['middleware' => 'auth'], function () {
    // Route yang membutuhkan autentikasi
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    Route::get('/sopir', [SopirController::class, 'index'])->name('sopir');
    Route::get('/show_sopir/{id}', [SopirController::class, 'show'])->name('showSopir');

    Route::get('/add_sopir', [SopirController::class, 'storeView']);
    Route::post('/add_sopir', [SopirController::class, 'store'])->name('addSopir');

    Route::get('/edit_sopir/{id}', [SopirController::class, 'editSopir'])->name('editSopir');

    Route::delete('/sopir/{id}', [SopirController::class, 'destroy'])->name('sopir.destroy');

    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});


