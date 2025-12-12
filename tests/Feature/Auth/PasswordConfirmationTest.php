<?php

use App\Models\Auth\User;

test('confirm password redirect to login if not authenticated', function () {
    $response = $this->get('/user/confirm-password');

    $response->assertRedirect('/login');
});

test('confirm password screen can be rendered', function () {
    $user = User::factory()->unverified()->create([
        'password' => bcrypt('password'),
    ]);

    $response = $this->actingAs($user)->get('/user/confirm-password');

    $response->assertStatus(200);
});

test('password can be confirmed', function () {
    $user = User::factory()->unverified()->create([
        'password' => bcrypt('password'),
    ]);

    $response = $this->actingAs($user)->post('/user/confirm-password', [
        'password' => 'password',
    ]);

    $response->assertRedirect();
    $response->assertSessionHasNoErrors();
});

test('password is not confirmed with invalid password', function () {
    $user = User::factory()->unverified()->create([
        'password' => bcrypt('password'),
    ]);

    $response = $this->actingAs($user)->post('/user/confirm-password', [
        'password' => 'wrong-password',
    ]);

    $response->assertSessionHasErrors();
});
