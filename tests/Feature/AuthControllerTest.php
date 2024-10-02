<?php

use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Illuminate\Support\Facades\Hash;


it('can register a new user', function () {
    $data = [
        'name' => 'Aouinti Mootez',
        'email' => 'mootez@gmail.com',
        'password' => '123456',
        'password_confirmation' => '123456',
    ];

    $response = $this->postJson('/register', $data);

    $response->assertStatus(200);
    $this->assertDatabaseHas('users', [
        'email' => 'mootez@gmail.com',
        'name' => 'Aouinti Mootez',
    ]);

    $response->assertJson([
        'user' => [
            'email' => 'mootez@gmail.com',
            'name' => 'Aouinti Mootez',
        ]
    ]);
});

it('can log in a user with correct credentials', function () {
    $user = User::create([
        'name' => 'Aouinti Mootez',
        'email' => 'mootez@gmail.com',
        'password' => Hash::make('123456'),
    ]);


    $response = $this->postJson('/login', [
        'email' => 'mootez@gmail.com',
        'password' => '123456',
    ]);

    $response->assertStatus(200);
    $response->assertJsonStructure([
        'user' => [
            'name',
            'email',
        ],
        'token',
    ]);

    $this->assertNotNull($response['token']);
});

it('returns an error with incorrect credentials', function () {
    $user = User::create([
        'name' => 'Aouinti Mootez',
        'email' => 'mootez@gmail.com',
        'password' => Hash::make('535dfdgd'),
    ]);

    $response = $this->postJson('/login', [
        'email' => 'mootez@gmail.com',
        'password' => 'wrongpassword',
    ]);

    $response->assertStatus(200);
    $response->assertJson([
        'message' => 'the provided credentials are incorrect',
    ]);
});

it('returns an error when email does not exist', function () {
    $response = $this->postJson('/login', [
        'email' => 'aaa@aaa.com',
        'password' => '123456',
    ]);

    $response->assertStatus(200);
    $response->assertJson([
        'message' => 'the provided credentials are incorrect',
    ]);
});

it('logs out the user and deletes tokens', function () {
    $user = User::create([
        'name' => 'Aouinti Mootez',
        'email' => 'mootez@gmail.com',
        'password' => bcrypt('123456'),
    ]);

    Sanctum::actingAs($user);

    $response = $this->postJson('/logout');

    $response->assertStatus(200);
    $response->assertJson([
        'message' => 'You are logged out',
    ]);

    $this->assertCount(0, $user->tokens);
});



