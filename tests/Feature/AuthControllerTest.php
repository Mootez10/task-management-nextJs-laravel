<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

// Test for user registration
it('can register a user', function () {
    $response = postJson('/register', [
        'name' => 'John Doe',
        'email' => 'johndoe@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertStatus(200); // or 302 for redirect
    $this->assertDatabaseHas('users', ['email' => 'johndoe@example.com']);
});

// Test for user login
it('can login a user', function () {
    $user = User::factory()->create([
        'email' => 'alias@gmail.com',
        'password' => Hash::make('123456'),
    ]);

    $response = postJson('/login', [
        'user' => $user,
        'token' => 'token',
    ]);

    $response->assertStatus(200); // or 302 if redirected
    $this->assertAuthenticated();
});

// Test for failed login
it('cannot login with invalid credentials', function () {
    $response = postJson('/login', [
        'email' => 'invalid@example.com',
        'password' => 'wrongpassword',
    ]);

    $response->assertStatus(200); // Unprocessable Entity for failed validation
    $this->assertGuest();
});

// Test for user logout
it('can logout a user', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $response = postJson('/logout');

    $response->assertStatus(200); // or 302 for redirect
    $this->assertGuest();
});
