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
    Route::get('/sopir/show/{id}', [SopirController::class, 'show'])->name('sopir.show');

    Route::get('/sopir/add', [SopirController::class, 'storeView']);
    Route::post('/sopir/add', [SopirController::class, 'store'])->name('sopir.store');

    Route::get('/sopir/edit/{id}', [SopirController::class, 'updateView']);
    Route::put('/sopir/edit/{id}', [SopirController::class, 'update'])->name('sopir.update');

    Route::delete('/sopir/delete/{id}', [SopirController::class, 'destroy'])->name('sopir.destroy');

    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});


