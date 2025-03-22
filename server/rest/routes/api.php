<?php

use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DestinationController;
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

// Public routes
Route::get('/destinations', [DestinationController::class, 'index']);
Route::get('/destinations/{destination}', [DestinationController::class, 'show']);

// Auth routes
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/logout', [AuthController::class, 'logout'])->middleware('auth:api');
Route::post('/auth/refresh', [AuthController::class, 'refresh'])->middleware('auth:api');
Route::get('/auth/me', [AuthController::class, 'me'])->middleware('auth:api');

// Admin routes (protected)
Route::middleware(['auth:api', 'admin'])->group(function () {
    // Destination management
    Route::post('/destinations', [DestinationController::class, 'store']);
    Route::put('/destinations/{destination}', [DestinationController::class, 'update']);
    Route::delete('/destinations/{destination}', [DestinationController::class, 'destroy']);

    // Admin dashboard
    Route::get('/admin/destinations', [AdminController::class, 'destinations']);
    Route::get('/admin/statistics', [AdminController::class, 'statistics']);
});