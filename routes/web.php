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

Route::get('/', function () {
    return view('welcome');
});
