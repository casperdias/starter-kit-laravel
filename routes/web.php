<?php

use App\Http\Controllers\Admin\HomeController as AdminHomeController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Content\NewsController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
})->name('home');

Route::middleware(['auth', 'verified', \Inertia\EncryptHistoryMiddleware::class])->group(function () {
    Route::get('dashboard', [HomeController::class, 'index'])->name('dashboard');

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('', AdminHomeController::class)->name('home');

        // User management
        Route::resource('users', UserController::class);
        Route::put('users/{user}/role/{role}', [UserController::class, 'updateRole'])->name('users.roles.update');
        Route::post('email/verification-notification/{user}', [EmailVerificationNotificationController::class, 'storeCustom'])
            ->middleware('throttle:6,1')
            ->name('verification.send.id');

        // Role management
        Route::resource('roles', RoleController::class);
        Route::put('roles/{role}/permission/{permission}', [RoleController::class, 'updatePermission'])->name('roles.permissions.update');

        // Permission management
        Route::resource('permissions', PermissionController::class);
    });

    // News management
    Route::resource('news', NewsController::class);
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
