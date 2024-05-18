<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\AuthController;
// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\API\UserController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ProductBookingController;
// use App\Http\Controllers\Api\ProductBookingController;
use App\Http\Controllers\Api\NewUserController;
use App\Http\Controllers\testController;

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

// Public accessible API
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('product-list', [ProductController::class, 'index'])->middleware('cache.headers:private;max_age=3600');
Route::get('index-user', [UserController::class, 'index'])->middleware('cache.headers:private;max_age=3600');

// Authenticated only API
// We use auth api here as a middleware so only authenticated user who can access the endpoint
// We use group so we can apply middleware auth api to all the routes within the group
Route::middleware('auth:api')->group(function() {
    Route::get('/me', [UserController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::post('user-store', [UserController::class, 'store']);
    Route::post('products/bulk-upload', [ProductController::class, 'bulkUpload']);
    Route::post('products/product-upload', [ProductController::class, 'productUpload']);
    Route::post('users/user-upload', [ProductController::class, 'userUpload']);
    Route::get('index', [ProductController::class, 'index']);
    Route::get('product-index', [ProductBookingController::class, 'productIndex']);//
    Route::post('store', [ProductController::class, 'store']);
    Route::post('update/{id?}', [ProductController::class, 'update']);
    Route::get('booking-index', [ProductBookingController::class, 'index']);//
    Route::get('index-admin', [ProductBookingController::class, 'indexAdmin']);
    Route::get('product-status', [ProductBookingController::class, 'productStatus']);
    Route::post('product-search', [ProductBookingController::class, 'productSearch']);
    Route::get('index-admin-approved', [ProductBookingController::class, 'indexAdminApproved']);
    Route::post('rollback-order-date', [ProductBookingController::class, 'rolebackOrderDate']);
    Route::post('rollback-order-time', [ProductBookingController::class, 'rolebackOrderTime']);
    Route::get('download-report', [ProductBookingController::class, 'downloadReport']);
    Route::post('download-report-next', [ProductBookingController::class, 'downloadReportNext']);
    Route::post('booking-store', [ProductBookingController::class, 'store']);
    Route::post('booking-store-admin', [ProductBookingController::class, 'storeAdmin']);
    Route::post('booking-update/{id?}', [ProductBookingController::class, 'update']);
    Route::delete('booking-destroy/{id?}', [ProductBookingController::class, 'destroy']);
    Route::get('user', [UserController::class, 'userDetails']);
    Route::post('user-update/{id?}', [UserController::class, 'update']);
    Route::get('logout', [UserController::class, 'userLogout']);
});