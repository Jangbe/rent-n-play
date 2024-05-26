<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\IdentityController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TestimonialController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('auth/register', [AuthController::class, 'register']);
Route::post('auth/login', [AuthController::class, 'login']);
Route::post('auth/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::post('auth/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('auth/reset-password', [AuthController::class, 'resetPassword'])->name('password.reset');

Route::get('popular-product', [DashboardController::class, 'popularProduct']);
Route::get('popular-product/{id}', [DashboardController::class, 'popularProductShow']);

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('/user', [ProfileController::class, 'user']);
    Route::post('/update-profile', [ProfileController::class, 'update_profile']);
    Route::post('/update-password', [ProfileController::class, 'update_password']);
    Route::post('/read-all-notification', [ProfileController::class, 'read_all_notification']);
    Route::post('/read-notification/{id}', [ProfileController::class, 'read_notification']);

    Route::get('dashboard/admin', [DashboardController::class, 'admin']);

    Route::get('identity', [IdentityController::class, 'index']);
    Route::post('identity', [IdentityController::class, 'store']);
    Route::get('identity/{user_id}', [IdentityController::class, 'show']);
    Route::put('identity/{id}', [IdentityController::class, 'update']);
    Route::delete('identity/{id}', [IdentityController::class, 'destroy']);

    Route::get('address', [AddressController::class, 'index']);
    Route::post('address', [AddressController::class, 'store']);
    Route::get('address/{user_id}', [AddressController::class, 'show']);
    Route::put('address/{id}', [AddressController::class, 'update']);
    Route::delete('address/{id}', [AddressController::class, 'destroy']);

    Route::get('transaction', [TransactionController::class, 'index']);
    Route::get('transaction/get-transaction-number', [TransactionController::class, 'getTransactionNumber']);
    Route::get('transaction/{id}', [TransactionController::class, 'show']);
    Route::post('transaction', [TransactionController::class, 'store']);
    Route::put('transaction/{transaction:transaction_number}', [TransactionController::class, 'update']);
    Route::post('transaction/{transaction:transaction_number}/midtrans-callback', [TransactionController::class, 'midtransCallback']);
    Route::delete('transaction/{id}', [TransactionController::class, 'destroy']);

    Route::get('category', [CategoryController::class, 'index'])->withoutMiddleware('auth:sanctum');
    Route::post('category', [CategoryController::class, 'store']);
    Route::put('category/{id}', [CategoryController::class, 'update']);
    Route::delete('category/{id}', [CategoryController::class, 'destroy']);

    Route::get('product', [ProductController::class, 'index']);
    Route::post('product', [ProductController::class, 'store']);
    Route::put('product/{id}', [ProductController::class, 'update']);
    Route::delete('product/{id}', [ProductController::class, 'destroy']);
    Route::post('carts', [ProductController::class, 'carts']);

    Route::get('master/user', [UserController::class, 'index']);

    Route::post('testimonial', [TestimonialController::class, 'store']);
});
Route::get('testimonial', [TestimonialController::class, 'index']);
