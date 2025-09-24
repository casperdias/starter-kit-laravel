<?php

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
