<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\EmailVerificationController;

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
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;

Route::get('/email-verified', [EmailVerificationController::class, 'verifyEmail'])->name('verification.verify');


Route::get('/', function () {
    return view('welcome');
});

// Route::get('/email-verified', function () {
//     return view('email-verified');
// });


