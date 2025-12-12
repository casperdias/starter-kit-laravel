<?php

use App\Models\Auth\User;
use Illuminate\Support\Facades\Hash;

test('password can be updated', function () {
    $user = User::factory()->unverified()->create([
        'password' => bcrypt('password'),
    ]);

    $user->markEmailAsVerified();

    $response = $this
        ->actingAs($user)
        ->from('/settings/password')
        ->put('/settings/password', [
            'current_password' => 'password',
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('/settings/password');

    expect(Hash::check('new-password', $user->refresh()->password))->toBeTrue();
});

test('correct password must be provided to update password', function () {
    $user = User::factory()->unverified()->create([
        'password' => bcrypt('password'),
    ]);

    $user->markEmailAsVerified();

    $response = $this
        ->actingAs($user)
        ->from('/settings/password')
        ->put('/settings/password', [
            'current_password' => 'wrong-password',
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ]);

    // dd($response->getContent());

    $response
        ->assertSessionHasErrors('current_password')
        ->assertRedirect('/settings/password');
});
