<?php

use App\Http\Controllers\API\BooksAPI;
use App\Http\Controllers\API\ReservationAPI;
use App\Http\Controllers\Auth\AuthController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
});

Route::controller(AuthController::class)->middleware("auth:api")->group(function () {
    Route::post('logout', 'logout');
    Route::post('refresh', 'refresh');
});

Route::get('/books', [BooksAPI::class, 'availableBooks']);
Route::get('/reservations', [ReservationAPI::class, 'activeReservations'])->middleware("auth:api");
Route::get('/reservations_histories', [ReservationAPI::class, 'pastReservations'])->middleware("auth:api");
Route::post('/reservate/{id}', [ReservationAPI::class, 'reservate'])->middleware("auth:api");
Route::post('/return/{id}', [ReservationAPI::class, 'return'])->middleware("auth:api");
Route::post('/cancel/{id}', [ReservationAPI::class, 'cancel'])->middleware("auth:api");
