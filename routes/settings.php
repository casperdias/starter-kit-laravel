<?php

use App\Http\Controllers\Settings\ChangeRoleController;
use App\Http\Controllers\Settings\NotificationController;
use App\Http\Controllers\Settings\PasswordController;
use App\Http\Controllers\Settings\ProfileController;
use App\Http\Controllers\Settings\TwoFactorAuthenticationController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware('auth', 'verified')->group(function () {
    Route::prefix('settings')->group(function () {
        // Redirect
        Route::redirect('', '/settings/profile');

        // Profile
        Route::controller(ProfileController::class)->prefix('profile')->name('profile.')->group(function () {
            Route::get('', 'edit')->name('edit');
            Route::patch('', 'update')->name('update');
            Route::delete('', 'destroy')->name('destroy');
        });

        // Password
        Route::controller(PasswordController::class)->prefix('password')->name('password.')->group(function () {
            Route::get('', 'edit')->name('edit');
            Route::put('', 'update')->name('update');
        });

        // Role
        Route::put('change-role', ChangeRoleController::class)->name('change-role');

        // Notifications
        Route::controller(NotificationController::class)->prefix('notifications')->name('notifications.')->group(function () {
            Route::get('', 'index')->name('index');
            Route::get('dropdown', 'dropdown')->name('dropdown');
            Route::post('mark-all-as-read', 'markAllAsRead')->name('mark-all-as-read');
            Route::post('mark-as-read', 'markAsRead')->name('mark-as-read');
            Route::post('{notification}/mark-as-read', 'markOneAsRead')->name('mark-one-as-read');
        });

        // Appearance
        Route::get('appearance', function () {
            return Inertia::render('settings/Appearance');
        })->name('appearance');

        // Two-Factor Authentication
        Route::controller(TwoFactorAuthenticationController::class)->prefix('two-factor')->name('two-factor.')->group(function () {
            Route::get('', 'show')->name('show');
        });
    });
});
