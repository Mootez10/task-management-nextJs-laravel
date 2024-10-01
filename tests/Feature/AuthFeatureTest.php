<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;


beforeEach(function () {
    // Run migrations and refresh the database for each test
    $this->artisan('migrate:fresh');
});

it('can register a new user', function () {
    // Arrange: Prepare the data for the registration request
    $data = [
        'name' => 'John Doe',
        'email' => 'john.doe@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ];

    // Act: Make the POST request to the /register endpoint
    $response = $this->postJson('/api/register', $data);

    // Assert: Verify that the response is successful and the user is created
    $response->assertStatus(200); // Or the expected status code (201, etc.)
    $this->assertDatabaseHas('users', [
        'email' => 'john.doe@example.com',
        'name' => 'John Doe',
    ]);
    
    // Optionally, check the response data
    $response->assertJson([
        'user' => [
            'email' => 'john.doe@example.com',
            'name' => 'John Doe',
        ]
    ]);

});


it('can log in a user with correct credentials', function () {
    // Arrange: Create a user in the database
    $user = User::create([
        'name' => 'Mootez aouinti',
        'email' => 'mootez@gmail.com',
        'password' => Hash::make('password123'),
    ]);

    // Act: Make a POST request to the /login endpoint with correct credentials
    $response = $this->postJson('/api/login', [
        'email' => 'mootez@gmail.com',
        'password' => 'password123',
    ]);

    // Assert: Verify the response and token
    $response->assertStatus(200); // Ensure the request was successful
    $response->assertJsonStructure([
        'user' => [
            'name',
            'email',
        ],
        'token', // Check that the token is in the response
    ]);

    // Optionally, check if the returned token is valid
    $this->assertNotNull($response['token']);
});

it('returns an error with incorrect credentials', function () {
    // Arrange: Create a user in the database
    $user = User::create([
        'name' => 'Mootez aouinti',
        'email' => 'mootez@gmail.com',
        'password' => Hash::make('password123'),
    ]);

    // Act: Make a POST request to the /login endpoint with incorrect credentials
    $response = $this->postJson('/api/login', [
        'email' => 'mootez@gmail.com',
        'password' => '123456',
    ]);

    // Assert: Ensure that an error message is returned
    $response->assertStatus(200); // Assuming the app returns a 200 status for incorrect credentials
    $response->assertJson([
        'message' => 'the provided credentials are incorrect',
    ]);
});

it('returns an error when email does not exist', function () {
    // Act: Try logging in with an email that does not exist
    $response = $this->postJson('/api/login', [
        'email' => 'unknown@example.com',
        'password' => 'password123',
    ]);

    // Assert: Ensure that an error message is returned
    $response->assertStatus(200); // Assuming the app returns a 200 status for incorrect email
    $response->assertJson([
        'message' => 'the provided credentials are incorrect',
    ]);
});


it('logs out the user and deletes tokens', function () {
    // Arrange: Create a user and authenticate using Sanctum
    $user = User::create([
        'name' => 'Mootez aouinti',
        'email' => 'mootez@gmail.com',
        'password' => bcrypt('password123'),
    ]);

    // Use Sanctum to authenticate the user
    Sanctum::actingAs($user);

    // Act: Make a POST request to the /logout endpoint
    $response = $this->postJson('/api/logout');

    // Assert: Ensure the tokens are deleted and a proper response is returned
    $response->assertStatus(200); // Ensure the request was successful
    $response->assertJson([
        'message' => 'You are logged out',
    ]);

    // Ensure that the user's tokens have been deleted
    $this->assertCount(0, $user->tokens);
});

it('returns an error if no user is authenticated', function () {
    // Act: Try to log out without an authenticated user
    $response = $this->postJson('/api/logout');

    // Assert: Ensure an unauthorized error response
    $response->assertStatus(401); // Assuming unauthenticated requests return a 401 status
});