<?php

use App\Http\Controllers\Admin\HomeController as AdminHomeController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
})->name('home');

Route::middleware(['auth', 'verified', \Inertia\EncryptHistoryMiddleware::class])->group(function () {
    Route::get('dashboard', [HomeController::class, 'index'])->name('dashboard');

    Route::prefix('admin')->name('admin.')->middleware('permission:admin')->group(function () {
        Route::get('', AdminHomeController::class)->name('home');

        Route::resources([
            'users' => UserController::class,
            'roles' => RoleController::class,
            'permissions' => PermissionController::class,
        ]);

        Route::prefix('users/{user}/role')->name('users.roles.')->group(function () {
            Route::put('{role}', [UserController::class, 'updateRole'])->name('update');
        });

        Route::prefix('roles/{role}/permission')->name('roles.permissions.')->group(function () {
            Route::put('{permission}', [RoleController::class, 'updatePermission'])->name('update');
        });

        Route::post('email/verification-notification/{user}', [EmailVerificationNotificationController::class, 'storeCustom'])
            ->middleware('throttle:6,1')
            ->name('verification.send.id');
    });
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
