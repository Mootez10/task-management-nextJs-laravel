<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Illuminate\Database\Eloquent\Factories\Factory;
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;

uses(RefreshDatabase::class);


// beforeEach(function () {
//     // Use the RefreshDatabase trait to reset the database after each test
//     $this->artisan('migrate:fresh');
// });

it('can fetch tasks for the authenticated user', function () {
    $user = User::create([
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => bcrypt('password'),
    ]);

    Sanctum::actingAs($user);

    $task = Task::create([
        'title' => 'Sample Task',
        'description' => 'simple simple',
        'status' => 'done',
        'due_date' => '12/12/2024',
        'user_id' => $user->id,
    ]);

    $this->assertTrue(auth()->check(), 'User is not authenticated.');

    $response = $this->getJson('/api/tasks');

    $response->dump(); 

    $response->assertStatus(200);

    $response->assertJsonFragment(['title' => $task->title]);
});


it('returns 401 for unauthorized requests', function () {
    $response = getJson('/api/tasks');

    $response->dump();

    $response->assertStatus(401);
});

it('can create a task for the authenticated user', function () {
    $user = User::create([
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => bcrypt('password'),
    ]);
    Sanctum::actingAs($user);

    $data = [
        'title' => 'Test Task',
        'description' => 'Test Description',
        'status' => 'pending',
        'due_date' =>'2020/12/12'
    ];



    $response = postJson('/api/tasks', $data);

    $response->assertStatus(200);
    $response->assertJsonFragment(['title' => 'Test Task']);
});

it('can update a task for the authenticated user', function () {
    $user = User::create([
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => bcrypt('password'),
    ]);

    Sanctum::actingAs($user);

    $task = Task::create([
        'title' => 'Original Task Title',
        'description' => 'Original Description',
        'status' => 'pending',
        'due_date' => '2024-12-12',
        'user_id' => $user->id,
    ]);

    $updatedData = [
        'title' => 'Updated Task Title',
        'description' => 'Updated Description',
        'status' => 'done',
        //'due_date' => '2024-12-15',
    ];

    $response = $this->putJson('/api/tasks/' . $task->id, $updatedData);

    $response->assertStatus(200);

    $response->assertJsonFragment(['title' => 'Updated Task Title']);
    $response->assertJsonFragment(['description' => 'Updated Description']);
    $response->assertJsonFragment(['status' => 'done']);
    //$response->assertJsonFragment(['due_date' => '2024-12-15']);


});

it('returns 404 if the task does not exist', function () {
    $user = User::create([
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => bcrypt('password'),
    ]);

    Sanctum::actingAs($user);

    $response = $this->putJson('/api/tasks/999', [
        'title' => 'Updated Task Title',
    ]);

    $response->assertStatus(404);

    $response->assertJsonFragment(['message' => 'Task not found']);
});

it('returns 403 if the user does not own the task', function () {
    $user1 = User::create([
        'name' => 'User One',
        'email' => 'user1@example.com',
        'password' => bcrypt('password'),
    ]);
    $user2 = User::create([
        'name' => 'User Two',
        'email' => 'user2@example.com',
        'password' => bcrypt('password'),
    ]);

    Sanctum::actingAs($user1);

    $task = Task::create([
        'title' => 'Task of User Two',
        'description' => 'User Two Description',
        'status' => 'pending',
        'due_date' => '2024-12-12',
        'user_id' => $user2->id,
    ]);

    $response = $this->putJson('/api/tasks/' . $task->id, [
        'title' => 'Updated Task Title',
    ]);

    $response->assertStatus(403);

    $response->assertJsonFragment(['message' => 'You do not own this task']);
});

it('can delete a task for the authenticated user', function () {
    $user = User::create([
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => bcrypt('password'),
    ]);

    Sanctum::actingAs($user);

    $task = Task::create([
        'title' => 'Task to be deleted',
        'description' => 'This task will be deleted',
        'status' => 'pending',
        'due_date' => '2024-12-12',
        'user_id' => $user->id,
    ]);

    $response = $this->deleteJson('/api/tasks/' . $task->id);

    $response->assertStatus(200);

    $response->assertJson(['message' => 'Task Deleted Successfully']);

    $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
});

it('returns a 404 error if the task does not exist', function () {
    $user = User::create([
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => bcrypt('password'),
    ]);

    Sanctum::actingAs($user);

    $response = $this->deleteJson('/api/tasks/999');

    $response->assertStatus(404);
    $response->assertJson(['message' => 'Task not found']);
});

it('returns a 403 error if the user does not own the task', function () {
    $user1 = User::create([
        'name' => 'Test User 1',
        'email' => 'test1@example.com',
        'password' => bcrypt('password'),
    ]);

    $user2 = User::create([
        'name' => 'Test User 2',
        'email' => 'test2@example.com',
        'password' => bcrypt('password'),
    ]);

    Sanctum::actingAs($user1);

    $task = Task::create([
        'title' => 'Task owned by User 2',
        'description' => 'User 2\'s task',
        'status' => 'pending',
        'due_date' => '2024-12-12',
        'user_id' => $user2->id,
    ]);

    $response = $this->deleteJson('/api/tasks/' . $task->id);

    $response->assertStatus(403);
    $response->assertJson(['message' => 'You do not have permission to delete this task']);
});


