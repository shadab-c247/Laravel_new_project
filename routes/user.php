<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;

// USER ROUTES - User panel routes with user middleware and module permission checks
Route::middleware(['auth', 'user', 'activity'])->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', [UserController::class, 'index'])
        ->name('dashboard');
    
    // User module routes - each requires specific module permission
    Route::get('/users', [AdminController::class, 'userUsers'])
        ->name('users')
        ->middleware('module.permission:users,view');
    Route::post('/users', [AdminController::class, 'storeUser'])
        ->name('users.store')
        ->middleware('module.permission:users,create');
    Route::delete('/users/{user}', [AdminController::class, 'destroyUser'])
        ->name('users.destroy')
        ->middleware('module.permission:users,delete');
    Route::put('/users/{user}/assignment', [AdminController::class, 'updateAssignment'])
        ->name('users.assignment.update')
        ->middleware('module.permission:users,edit');
    Route::get('/departments', [AdminController::class, 'userDepartments'])
        ->name('departments')
        ->middleware('module.permission:departments,view');
    Route::post('/departments', [AdminController::class, 'storeDepartment'])
        ->name('departments.store')
        ->middleware('module.permission:departments,create');
    Route::put('/departments/{department}', [AdminController::class, 'updateDepartment'])
        ->name('departments.update')
        ->middleware('module.permission:departments,edit');
    Route::delete('/departments/{department}', [AdminController::class, 'destroyDepartment'])
        ->name('departments.destroy')
        ->middleware('module.permission:departments,delete');
    
    Route::get('/roles', [AdminController::class, 'userRoles'])
        ->name('roles')
        ->middleware('module.permission:roles,view');
    Route::post('/roles', [AdminController::class, 'storeRole'])
        ->name('roles.store')
        ->middleware('module.permission:roles,create');
    Route::put('/roles/{role}', [AdminController::class, 'updateRole'])
        ->name('roles.update')
        ->middleware('module.permission:roles,edit');
    Route::delete('/roles/{role}', [AdminController::class, 'destroyRole'])
        ->name('roles.destroy')
        ->middleware('module.permission:roles,delete');
    
    Route::get('/positions', [AdminController::class, 'userPositions'])
        ->name('positions')
        ->middleware('module.permission:positions,view');
    Route::post('/positions', [AdminController::class, 'storePosition'])
        ->name('positions.store')
        ->middleware('module.permission:positions,create');
    Route::put('/positions/{position}', [AdminController::class, 'updatePosition'])
        ->name('positions.update')
        ->middleware('module.permission:positions,edit');
    Route::delete('/positions/{position}', [AdminController::class, 'destroyPosition'])
        ->name('positions.destroy')
        ->middleware('module.permission:positions,delete');
    
    Route::get('/activity-logs', [AdminController::class, 'userActivityLogs'])
        ->name('activity-logs')
        ->middleware('module.permission:activity-logs,view');
});