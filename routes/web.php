<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'activity'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');
});

// ADMIN ROUTES
Route::middleware(['auth', 'admin', 'activity'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])
        ->name('dashboard');
    Route::post('/users', [AdminController::class, 'storeUser'])
        ->name('users.store');
    Route::put('/users/{user}/assignment', [AdminController::class, 'updateAssignment'])
        ->name('users.assignment.update');
    Route::delete('/users/{user}', [AdminController::class, 'destroyUser'])
        ->name('users.destroy');
    Route::post('/users/{user}/switch', [AdminController::class, 'switchUser'])
        ->name('users.switch');
    Route::get('/activity-logs', [AdminController::class, 'activityLogs'])
        ->name('activity-logs');
});

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::post('/switch-back', [AdminController::class, 'switchBack'])
        ->name('switch-back');
});

// USER ROUTES
Route::middleware(['auth', 'activity'])->group(function () {
    Route::get('/user/dashboard', [UserController::class, 'index'])
        ->name('user.dashboard');
});

Route::post('/verify-otp', [AuthController::class, 'verifyOtp'])->name('verify.otp');
Route::get('/otp', function () {
    return view('otp');
})->name('otp.form');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
