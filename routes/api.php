<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//public routes
Route::post('/sanctum/login', [AuthController::class, 'getToken']);

Route::post('register/user', [SessionController::class, 'registerPerson']);

//private routes
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/sanctum/user', [AuthController::class, 'getUser']);
    Route::delete('/sanctum/revoke', [AuthController::class, 'revokeUser']);
});
