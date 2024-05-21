<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\IdentityController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductTransactionController;
use App\Http\Controllers\TransactionController;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('/user', [ProfileController::class, 'user']);
    Route::post('/update-profile', [ProfileController::class, 'update_profile']);
    Route::post('/update-password', [ProfileController::class, 'update_password']);

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
    Route::put('transaction/{id}', [TransactionController::class, 'update']);
    Route::delete('transaction/{id}', [TransactionController::class, 'destroy']);

    Route::get('category', [CategoryController::class, 'index'])->withoutMiddleware('auth:sanctum');
    Route::post('category', [CategoryController::class, 'store']);
    Route::put('category/{id}', [CategoryController::class, 'update']);
    Route::delete('category/{id}', [CategoryController::class, 'destroy']);

    Route::get('product', [ProductController::class, 'index'])->withoutMiddleware('auth:sanctum');
    Route::post('product', [ProductController::class, 'store']);
    Route::put('product/{id}', [ProductController::class, 'update']);
    Route::delete('product/{id}', [ProductController::class, 'destroy']);
});
