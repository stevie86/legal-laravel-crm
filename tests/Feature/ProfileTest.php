<?php

use App\Models\User;
use function PHPUnit\Framework\assertNull;
use function PHPUnit\Framework\assertNotNull;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertGuest;

test('profile page is displayed', function () {
    $user = User::factory()->create();

    actingAs($user)->get('/profile')->assertOk();
});

test('profile information can be updated', function () {
    $user = User::factory()->create();

    $response = actingAs($user)->patch('/profile', [
        'name' => 'Test User',
        'email' => 'test@example.com',
    ]);

    $response->assertSessionHasNoErrors()->assertRedirect('/profile');

    $user->refresh();

    expect($user->name)->toBe('Test User');
    expect($user->email)->toBe('test@example.com');
    assertNull($user->email_verified_at);
});

test('email verification status is unchanged when the email address is unchanged', function () {
    $user = User::factory()->create();

    $response = actingAs($user)->patch('/profile', [
        'name' => 'Test User',
        'email' => $user->email,
    ]);

    $response->assertSessionHasNoErrors()->assertRedirect('/profile');

    assertNotNull($user->refresh()->email_verified_at);
});

test('user can delete their account', function () {
    $user = User::factory()->create();

    $response = actingAs($user)->delete('/profile', [
        'password' => 'password',
    ]);

    $response->assertSessionHasNoErrors()->assertRedirect('/');

    assertGuest();
    assertNull($user->fresh());
});

test('correct password must be provided to delete account', function () {
    $user = User::factory()->create();

    $response = actingAs($user)->from('/profile')->delete('/profile', [
        'password' => 'wrong-password',
    ]);

    $response->assertSessionHasErrorsIn('userDeletion', 'password')->assertRedirect('/profile');

    assertNotNull($user->fresh());
});
