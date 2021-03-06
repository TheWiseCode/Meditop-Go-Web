<?php

use App\Http\Controllers\Api\VerificationController;
use App\Http\Controllers\Web\ConsultController;
use App\Http\Controllers\Web\DoctorController;
use App\Http\Controllers\Web\PacientController;
use App\Http\Controllers\Web\ReservationController;
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
    ->middleware(['signed'])->name('verification.verify');

Route::post('/email/verification-notification', [VerificationController::class, 'notification'])
    ->middleware(['auth:sanctum', 'throttle:6,1'])->name('verification.send');
//-----------------------------

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::resource('users', UserController::class)
        ->parameters(['users' => 'user'])->names('users');

    Route::get('/create-admin', [UserController::class, 'createAdmin'])->name('create-admin');
    Route::post('/store-admin', [UserController::class, 'storeAdmin'])->name('store-admin');
    Route::get('/doctor-requests', [UserController::class, 'doctorRequests'])->name('doctor-requests');
    Route::get('/doctor-verification/{person}', [UserController::class, 'doctorVerification'])->name('doctor-verification');
    Route::post('/doctor-verified-accept', [UserController::class, 'acceptVerification'])->name('accept-verification');
    Route::post('/doctor-verified-denied', [UserController::class, 'deniedVerification'])->name('denied-verification');

    Route::get('/schedule', [DoctorController::class, 'schedule'])
        ->name('doctor-schedule');
    Route::get('/add-schedule', [DoctorController::class, 'addSchedule'])
        ->name('doctor-add-schedule');
    Route::post('/register-schedule', [DoctorController::class, 'registerSchedule'])
        ->name('doctor-register-schedule');
    Route::get('/edit-schedule/{offer}', [DoctorController::class, 'editSchedule'])
        ->name('doctor-edit-schedule');
    Route::put('/update-schedule/{offer}', [DoctorController::class, 'updateSchedule'])
        ->name('doctor-update-schedule');
    Route::delete('/schedule/{offer}', [DoctorController::class, 'deleteSchedule'])
        ->name('doctor-delete-schedule');

    Route::resource('reservations', ReservationController::class)->parameters(['reservation' => 'reservation'])
        ->names('reservations');
    Route::get('/reservations-filter', [ReservationController::class, 'getByFilter']);
    Route::get('/admin-reservations-filter', [ReservationController::class, 'adminByFilter']);
    Route::post('/doctor-reservation-accept', [ReservationController::class, 'acceptReservation'])->name('accept-reservation');
    Route::post('/doctor-reservation-denied', [ReservationController::class, 'deniedReservation'])->name('denied-reservation');
    Route::post('/doctor-reservation-cancel', [ReservationController::class, 'cancelReservation'])->name('cancel-reservation');

    Route::get('/admin-reservations', [ReservationController::class, 'viewReservations'])->name('admin.reservations');

    Route::resource('consults', ConsultController::class)->parameters(['consult' => 'consult'])
        ->names('consults');
    Route::post('/consult-start/{consult}', [ConsultController::class, 'startConsult'])->name('consult.start');
    Route::post('/consult-cancel', [ConsultController::class, 'cancelConsult'])->name('consult.cancel');

    Route::get('/medical-history/{patient}', [PacientController::class, 'historial'])
        ->name('patient.historial');
});
