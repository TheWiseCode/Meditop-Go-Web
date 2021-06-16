<?php

use App\Http\Controllers\Api\SessionController;
use App\Http\Controllers\Api\TransactionController;
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

//private routes
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/user', [SessionController::class, 'getUser']);
    Route::delete('/logout', [SessionController::class, 'logout']);

    Route::post('/transaction/pay', [TransactionController::class, 'abonar']);
    Route::post('/transaction/extract', [TransactionController::class, 'retirar']);
});
