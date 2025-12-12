<?php

use App\Actions\Admin\AssignRole;
use App\Models\Auth\Role;
use App\Models\Auth\User;

test('guests are redirected to the login page', function () {
    $response = $this->get('/dashboard');
    $response->assertRedirect('/login');
});

test('authenticated but unverified users are redirected to the email verification notice', function () {
    $user = User::create([
        'name' => 'Test User',
        'email' => 'test@test.com',
        'password' => bcrypt('password'),
    ]);
    $this->actingAs($user);

    $response = $this->get('/dashboard');
    $response->assertRedirect('/verify-email');
});

test('authenticated and verified users can visit the dashboard', function () {
    $user = User::create([
        'name' => 'Test User',
        'email' => 'test@test.com',
        'password' => bcrypt('password'),
    ]);
    $user->markEmailAsVerified();
    $this->actingAs($user);

    $response = $this->get('/dashboard');
    $response->assertStatus(200);
});

describe('changing role', function () {
    test('user can switch roles', function () {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@test.com',
            'password' => bcrypt('password'),
        ]);
        $user->markEmailAsVerified();

        $role1 = Role::create(['name' => 'Role 1', 'display_name' => 'Role 1', 'description' => 'Role 1']);
        $role2 = Role::create(['name' => 'Role 2', 'display_name' => 'Role 2', 'description' => 'Role 2']);

        $assignRole = new AssignRole;

        $assignRole->handle($user, $role1);
        $assignRole->handle($user, $role2);

        $this->actingAs($user)
            ->put(route('change-role'), ['role' => $role2->id])
            ->assertRedirect();

        $this->assertEquals($role2->id, $user->fresh()->role->id);
    });

    test('user cannot change role that not assigned', function () {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@test.com',
            'password' => bcrypt('password'),
        ]);
        $user->markEmailAsVerified();

        $role1 = Role::create(['name' => 'Role 1', 'display_name' => 'Role 1', 'description' => 'Role 1']);
        $role2 = Role::create(['name' => 'Role 2', 'display_name' => 'Role 2', 'description' => 'Role 2']);

        $assignRole = new AssignRole;

        $assignRole->handle($user, $role1);

        $this->actingAs($user)
            ->put(route('change-role'), ['role' => $role2->id])
            ->assertSessionHasErrors('message');

        $this->assertNotEquals($role2->id, $user->fresh()->role->id);
    });

    test('user cannot change role that not found', function () {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@test.com',
            'password' => bcrypt('password'),
        ]);
        $user->markEmailAsVerified();

        $role1 = Role::create(['name' => 'Role 1', 'display_name' => 'Role 1', 'description' => 'Role 1']);

        $assignRole = new AssignRole;

        $assignRole->handle($user, $role1);

        $this->actingAs($user)
            ->put(route('change-role'), ['role' => 999])
            ->assertSessionHasErrors('role');
    });
});
