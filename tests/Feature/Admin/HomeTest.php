<?php

use App\Models\Auth\User;

describe('admin can access admin home', function () {
    test('user', function () {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
        ]);
        $admin->markEmailAsVerified();

        Gate::define('user', fn ($user) => $user);

        $this->actingAs($admin)
            ->get(route('admin.home'))
            ->assertSuccessful();
    });

    test('role', function () {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
        ]);
        $admin->markEmailAsVerified();

        Gate::define('role', fn ($user) => $user);

        $this->actingAs($admin)
            ->get(route('admin.home'))
            ->assertSuccessful();
    });

    test('permission', function () {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
        ]);
        $admin->markEmailAsVerified();

        Gate::define('permission', fn ($user) => $user);

        $this->actingAs($admin)
            ->get(route('admin.home'))
            ->assertSuccessful();
    });
});

test('non-admin user is forbidden from admin home', function () {
    $user = User::create([
        'name' => 'Regular User',
        'email' => 'user@test.com',
        'password' => bcrypt('password'),
    ]);
    $user->markEmailAsVerified();

    Gate::define('test', fn ($user) => $user);

    $this->actingAs($user)
        ->get(route('admin.home'))
        ->assertForbidden();
});

test('guest cannot access admin home', function () {
    $this->get(route('admin.home'))
        ->assertRedirect(route('login'));
});
