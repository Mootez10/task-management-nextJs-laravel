<?php


use Laravel\Sanctum\Sanctum;
use App\Models\User;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can fetch tasks for the authenticated user', function () {
    $user = User::create([
        'name' => 'Aouinti Mootez',
        'email' => 'mootez@gmail.com',
        'password' => bcrypt('123456'),
    ]);

    Sanctum::actingAs($user);

    $task = Task::create([
        'title' => 'Task for mootez',
        'description' => 'task for mootez',
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

it('can create a task for the authenticated user', function () {
    $user = User::create([
        'name' => 'Aouinti Mootez',
        'email' => 'mootez@gmail.com',
        'password' => bcrypt('123456'),
    ]);

    Sanctum::actingAs($user);

    $this->assertTrue(auth()->check(), 'User is not authenticated.');

    $data = [
        'title' => 'Test Task',
        'description' => 'Test Description',
        'status' => 'pending',
        'due_date' => '2020/12/12',
    ];

    $response = $this->postJson('/api/tasks', $data);
    $response->dump();
    $response->assertStatus(200);
    $response->assertJsonFragment(['title' => 'Test Task']);
});

it('can update a task for the authenticated user', function () {
    $user = User::create([
        'name' => 'Aouinti Mootez',
        'email' => 'mootez@gmail.com',
        'password' => bcrypt('123456'),
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
    ];


    $response = $this->putJson('/api/tasks/' . $task->id, $updatedData);

    $response->assertStatus(200);
    $response->assertJsonFragment(['title' => 'Updated Task Title']);
    $response->assertJsonFragment(['description' => 'Updated Description']);
    $response->assertJsonFragment(['status' => 'done']);

});

it('can delete a task for the authenticated user', function () {
    $user = User::create([
        'name' => 'Aouinti Mootez',
        'email' => 'mootez@gmail.com',
        'password' => bcrypt('123456'),
    ]);

    Sanctum::actingAs($user);

    $task = Task::create([
        'title' => 'Task to be deleted',
        'description' => 'task will be deleted',
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
        'name' => 'Aouinti Mootez',
        'email' => 'mootez@gmail.com',
        'password' => bcrypt('123456'),
    ]);

    Sanctum::actingAs($user);

    $response = $this->deleteJson('/api/tasks/951');

    $response->assertStatus(404);
    $response->assertJson(['message' => 'Task not found']);
});

it('returns a 403 error if the user does not own the task', function () {
    $user1 = User::create([
        'name' => 'Test Mootez ',
        'email' => 'testmootez@gmail.com',
        'password' => bcrypt('123456'),
    ]);

    $user2 = User::create([
        'name' => 'Test Dorra',
        'email' => 'testdorra@gmail.com',
        'password' => bcrypt('123'),
    ]);

    Sanctum::actingAs($user1);

    $task = Task::create([
        'title' => 'Task owned by Dorra',
        'description' => 'Dorra task',
        'status' => 'pending',
        'due_date' => '2024-12-12',
        'user_id' => $user2->id,
    ]);

    $response = $this->deleteJson('/api/tasks/' . $task->id);
    $response->assertStatus(403);
    $response->assertJson(['message' => 'You do not have permission to delete this task']);
});





