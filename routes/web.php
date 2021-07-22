<?php

use App\Http\Controllers\CarController;
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

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('add', [CarController::class, 'create']);
Route::post('add', [CarController::class, 'store']);
Route::get('car', [CarController::class, 'index']);
Route::get('edit/{id}', [CarController::class, 'edit'])->name('editcar');
Route::post('edit/{id}', [CarController::class, 'update']);
Route::delete('{id}', [CarController::class, 'destroy'])->name('destroycar');
