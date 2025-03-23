<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DestinationWebController;
use App\Http\Controllers\Auth\AdminAuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public routes
// Route::get('/', [DestinationWebController::class, 'home'])->name('home');
// Route::get('/destinations/{destination}', [DestinationWebController::class, 'details'])->name('destinations.details');

// Authentication routes
Route::get('/admin/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login']);
Route::get('/admin/register', [AdminAuthController::class, 'showRegistrationForm'])->name('admin.register');
Route::post('/admin/register', [AdminAuthController::class, 'register']);
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

// Admin routes (protected)
Route::prefix('admin')->middleware('auth')->name('admin.')->group(function () {
    Route::resource('destinations', DestinationWebController::class);
});

// Route fallback - doit être définie à la fin du fichier
Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});
