<?php

use App\Http\Controllers\Api\VerificationController;
use App\Http\Controllers\Web\UserController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::group(["middleware" => ["auth:sanctum", "verified"]], function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
});

//Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();

//----------VERIFICACION DE CORREO
Route::get('/email/verify', [VerificationController::class, 'showVerify'])
->middleware('auth:sanctum')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', [VerificationController::class, 'verify'])
->middleware([ 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', [VerificationController::class, 'notification'])
->middleware(['auth:sanctum', 'throttle:6,1'])->name('verification.send');
//-----------------------------

Route::get('/mobile/email/resend', [VerificationController::class, 'resend'])
->name('verification.resend.mobile');









//-------------


Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::resource('users', UserController::class)
        ->parameters(['users' => 'user'])->names('users');

    Route::get('/create-admin', [UserController::class, 'createAdmin'])->name('create-admin');
    Route::post('/store-admin', [UserController::class, 'storeAdmin'])->name('store-admin');
    Route::get('/doctor-requests', [UserController::class, 'doctorRequests'])->name('doctor-requests');
    Route::get('/doctor-verification/{person}', [UserController::class, 'doctorVerification'])->name('doctor-verification');
});
