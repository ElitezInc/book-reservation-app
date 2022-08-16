<?php

use App\Http\Controllers\Dashboard\BooksController;
use App\Http\Controllers\Dashboard\HomeController;
use App\Http\Controllers\Dashboard\ReservationController;
use App\Http\Controllers\Dashboard\ReservationHistoriesController;
use App\Http\Controllers\Dashboard\UsersController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;

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
});

Auth::routes(['register' => false, 'reset' => false]);

Route::get('language/{locale}', function ($locale) {
    app()->setLocale($locale);
    session()->put('locale', $locale);
    return redirect()->back();
});

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['admin']], function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');

    Route::get('/users', [UsersController::class, 'index'])->name('users');
    Route::get('/users/{id}', [UsersController::class, 'show'])->name('user_show');
    Route::get('/users/edit/{id}', [UsersController::class, 'edit'])->name('user_edit');
    Route::put('/users/update/{id}', [UsersController::class, 'updateDetails'])->name('user_details_update');
    Route::delete('/users/delete/{id}', [UsersController::class, 'delete'])->name('user_delete');
    Route::delete('/users/destroy/{id}', [UsersController::class, 'destroy'])->name('user_destroy');
    Route::post('/users/restore/{id}', [UsersController::class, 'restore'])->name('user_restore');

    Route::get('/books', [BooksController::class, 'index'])->name('books');
    Route::delete('/books/delete/{id}', [BooksController::class, 'delete'])->name('book_delete');
    Route::delete('/books/destroy/{id}', [BooksController::class, 'destroy'])->name('book_destroy');
    Route::post('/books/restore/{id}', [BooksController::class, 'restore'])->name('book_restore');

    Route::get('/reservations', [ReservationController::class, 'index'])->name('reservations');
    Route::post('/reservations/return/{id}', [ReservationController::class, 'return'])->name('reservation_return');
    Route::post('/reservations/cancel/{id}', [ReservationController::class, 'cancel'])->name('reservation_cancel');
    Route::delete('/reservations/delete/{id}', [ReservationController::class, 'delete'])->name('reservation_delete');
    Route::delete('/reservations/destroy/{id}', [ReservationController::class, 'destroy'])->name('reservation_destroy');
    Route::post('/reservations/restore/{id}', [ReservationController::class, 'restore'])->name('reservation_restore');

    Route::get('/reservations_histories', [ReservationHistoriesController::class, 'index'])->name('reservations_histories');
    Route::delete('/reservations_histories/delete/{id}', [ReservationHistoriesController::class, 'delete'])->name('reservations_histories_delete');
    Route::delete('/reservations_histories/destroy/{id}', [ReservationHistoriesController::class, 'destroy'])->name('reservations_histories_destroy');
    Route::post('/reservations_histories/restore/{id}', [ReservationHistoriesController::class, 'restore'])->name('reservations_histories_restore');
});
