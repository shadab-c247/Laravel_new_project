<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

// USER ROUTES
Route::middleware(['auth', 'activity'])->group(function () {
    Route::get('/user/dashboard', [UserController::class, 'index'])
        ->name('user.dashboard');
    
});