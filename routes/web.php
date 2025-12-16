<?php

use App\Http\Controllers\Admin\HomeController as AdminHomeController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Content\ChatController;
use App\Http\Controllers\Content\NewsController;
use App\Http\Controllers\ExternalController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use Inertia\EncryptHistoryMiddleware;

Route::get('/', function () {
    return redirect()->route('dashboard');
})->name('home');

Route::middleware(['auth', 'verified', EncryptHistoryMiddleware::class])->group(function () {
    Route::get('dashboard', [HomeController::class, 'index'])->name('dashboard');

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('', AdminHomeController::class)->name('home');

        // User management
        Route::resource('users', UserController::class)->only(['index', 'store', 'show', 'edit', 'update', 'destroy']);
        Route::put('users/{user}/role/{role}', [UserController::class, 'updateRole'])->name('users.roles.update');
        Route::post('email/verification-notification/{user}', [EmailVerificationNotificationController::class, 'storeCustom'])
            ->middleware('throttle:6,1')
            ->name('verification.send.id');

        // Role management
        Route::resource('roles', RoleController::class)->only(['index', 'store', 'show', 'edit', 'update', 'destroy']);
        Route::put('roles/{role}/permission/{permission}', [RoleController::class, 'updatePermission'])->name('roles.permissions.update');

        // Permission management
        Route::resource('permissions', PermissionController::class)->only(['index', 'store', 'show', 'edit', 'update', 'destroy']);
    });

    // News management
    Route::resource('news', NewsController::class)->only(['index', 'create', 'store', 'show']);

    // Chat
    Route::resource('chats', ChatController::class);

    // External
    Route::controller(ExternalController::class)->prefix('external')->name('external.')->group(function () {
        Route::get('user-list', 'userList')->name('user-list');
    });
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
