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

Route::get('/login', [LoginController::class, 'loginForm']);
Route::post('/login', [LoginController::class, 'loginVerify'])->name('login');

Route::middleware(['auth'])->group(function () {
    // Route yang membutuhkan autentikasi
    Route::get('/home', 'HomeController@index')->name('home');

    Route::get('/sopir', [SopirController::class, 'index'])->name('sopir');
    Route::get('/show_sopir/{id}', [SopirController::class, 'show'])->name('showSopir');
    Route::get('/add_sopir', [SopirController::class, 'addSopirView']);
    Route::post('/add_sopir', [SopirController::class, 'addSopir'])->name('addSopir');
    Route::get('/edit_sopir/{id}', [SopirController::class, 'editSopir'])->name('editSopir');

    Route::get('/profile', 'ProfileController@index')->name('profile');
    Route::put('/profile', 'ProfileController@update')->name('profile.update');

    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});


