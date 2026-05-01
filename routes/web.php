<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
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
    Route::get('/users', [AdminController::class, 'users'])
        ->name('users');
    Route::get('/departments', [AdminController::class, 'departments'])
        ->name('departments');
    Route::post('/departments', [AdminController::class, 'storeDepartment'])
        ->name('departments.store');
    Route::put('/departments/{department}', [AdminController::class, 'updateDepartment'])
        ->name('departments.update');
    Route::delete('/departments/{department}', [AdminController::class, 'destroyDepartment'])
        ->name('departments.destroy');
    Route::get('/roles', [AdminController::class, 'roles'])
        ->name('roles');
    Route::post('/roles', [AdminController::class, 'storeRole'])
        ->name('roles.store');
    Route::put('/roles/{role}', [AdminController::class, 'updateRole'])
        ->name('roles.update');
    Route::delete('/roles/{role}', [AdminController::class, 'destroyRole'])
        ->name('roles.destroy');
    Route::get('/positions', [AdminController::class, 'positions'])
        ->name('positions');
    Route::post('/positions', [AdminController::class, 'storePosition'])
        ->name('positions.store');
    Route::put('/positions/{position}', [AdminController::class, 'updatePosition'])
        ->name('positions.update');
    Route::delete('/positions/{position}', [AdminController::class, 'destroyPosition'])
        ->name('positions.destroy');
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
    Route::get('/activities/export', [AdminController::class, 'export'])
        ->name('activities.export');
});

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::post('/switch-back', [AdminController::class, 'switchBack'])
        ->name('switch-back');
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
require __DIR__.'/user.php';
