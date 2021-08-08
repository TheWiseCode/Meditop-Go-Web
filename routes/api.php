<?php


use App\Http\Controllers\Api\SessionController;
use App\Http\Controllers\Api\VerificationController;
use App\Http\Controllers\OfferDaysController;
use App\Http\Controllers\Web\ReservationController;
use App\Http\Controllers\Web\SpecialtyController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//public routes
Route::post('/register/user', [SessionController::class, 'registerPerson']);
Route::post('/login', [SessionController::class, 'login']);

Route::get('/find-email', [SessionController::class, 'findEmail']);

Route::post('/email/verification-notification', [VerificationController::class, 'resend']);


Route::get('/specialties', [SpecialtyController::class, 'getAll']);
Route::post('/schedules', [OfferDaysController::class, 'getBySpecialty']);
Route::post('/offers', [OfferDaysController::class, 'getDaysAvailable']);
Route::post('/verified-reservation', [ReservationController::class, 'verifiedReservation']);
Route::post('/do-reservation', [ReservationController::class, 'doReservation']);
//Route::post('/get-pending', [ReservationController::class, 'getPending']);

Route::post('/test', [ReservationController::class, 'test']);

//private routes
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/user', [SessionController::class, 'getUser']);
    Route::delete('/logout', [SessionController::class, 'logout']);

    Route::get('/get-pending', [ReservationController::class, 'getPending']);
    Route::get('/get-scheduled', [ReservationController::class, 'getScheduled']);
    /*Route::post('/account/open', [AccountController::class, 'abrir']);
    Route::get('/accounts', [AccountController::class, 'getCuentas']);
    Route::post('/transaction/deposit', [TransactionController::class, 'abonar']);
    Route::post('/transaction/extract', [TransactionController::class, 'retirar']);*/
});
