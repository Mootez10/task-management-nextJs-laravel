<?php

use App\Models\SubTask;
use Laravel\Sanctum\Sanctum;
use App\Models\User;
use App\Models\Task;

it('can fetch all subtasks for the authenticated user', function () {
    $user = User::create([
        'name' => 'Mootez Aouinti',
        'email' => 'mootez@gmail.com',
        'password' => bcrypt('123456'),
    ]);

    Sanctum::actingAs($user);

    $task = Task::create([
        'title' => 'Task for Mootez',
        'description' => 'task description',
        'status' => 'pending',
        'user_id' => $user->id,
    ]);

    SubTask::create([
        'task_id' => $task->id,
        'title' => 'Subtask 1 for task mootez',
        'description' => 'Description for Subtask 1',
        'status' => 'pending'
    ]);

    SubTask::create([
        'task_id' => $task->id,
        'title' => 'Subtask 2 for task mootez',
        'description' => 'Description for Subtask 2',
        'status' => 'done'
    ]);

    $response = $this->getJson('/api/subtasks');
    $response->assertStatus(200);
    $response->assertJsonFragment(['title' => 'Subtask 1 for task mootez']);
    $response->assertJsonFragment(['title' => 'Subtask 2 for task mootez']);
});


it('can create a subtask for a specific task', function () {
    $user = User::create([
        'name' => 'Test User',
        'email' => 'test@gmail.com',
        'password' => bcrypt('123'),
    ]);

    Sanctum::actingAs($user);

    $task = Task::create([
        'title' => 'Task for mootez',
        'description' => 'Main task description',
        'status' => 'pending',
        'user_id' => $user->id,
    ]);

    $data = [
        'task_id' => $task->id,
        'title' => 'SubTask for mootez ',
        'description' => 'SubTask Description',
        'status' => 'pending',
    ];

    $response = $this->postJson('/api/subtasks', $data);
    $response->assertStatus(201);
    $response->assertJsonFragment(['title' => 'SubTask for mootez']);
});

it('can fetch a subtask by its id', function () {
    $user = User::create([
        'name' => 'Mootez Aouinti',
        'email' => 'mootez@gmail.com',
        'password' => bcrypt('123456'),
    ]);

    Sanctum::actingAs($user);

    $task = Task::create([
        'title' => 'Task for Mootez',
        'description' => 'task description',
        'status' => 'pending',
        'user_id' => $user->id,
    ]);

    $subTask = SubTask::create([
        'task_id' => $task->id,
        'title' => 'Subtask Title',
        'description' => 'Subtask Description',
        'status' => 'pending',
    ]);

    $response = $this->getJson('/api/subtasks/' . $subTask->id);

    $response->assertStatus(200);
    $response->assertJsonFragment(['title' => 'Subtask Title']);
});



it('can update a subtask', function () {
    $user = User::create([
        'name' => 'Mootez Aouinti',
        'email' => 'mootez@gmail.com',
        'password' => bcrypt('123456'),
    ]);

    Sanctum::actingAs($user);

    $task = Task::create([
        'title' => 'Task for Mootez',
        'description' => 'task description',
        'status' => 'pending',
        'user_id' => $user->id,
    ]);

    $subTask = SubTask::create([
        'task_id' => $task->id,
        'title' => 'Subtask Title',
        'description' => 'Subtask Description',
        'status' => 'pending',
    ]);

    $updatedData = [
        'title' => 'Updated Subtask Title',
        'description' => 'Updated Description',
        'status' => 'done',
    ];

    $response = $this->putJson('/api/subtasks/' . $subTask->id, $updatedData);
    $response->assertStatus(200);

});


it('can delete a subtask', function () {
    $user = User::create([
        'name' => 'Mootez Aouinti',
        'email' => 'mootez@gmail.com',
        'password' => bcrypt('123456'),
    ]);

    Sanctum::actingAs($user);

    $task = Task::create([
        'title' => 'Task for Mootez',
        'description' => 'Task description',
        'status' => 'pending',
        'user_id' => $user->id,
    ]);

    $subTask = SubTask::create([
        'task_id' => $task->id,
        'title' => 'Subtask Title',
        'description' => 'Subtask Description',
        'status' => 'pending',
    ]);

    $response = $this->deleteJson('/api/subtasks/' . $subTask->id);
    $response->assertStatus(200);
    $this->assertDatabaseMissing('sub_tasks', ['id' => $subTask->id]);
    $response->assertJsonFragment(['message' => 'SubTask deleted successfully']);
});



it('can fetch subtasks by task ID', function () {
    $user = User::create([
        'name' => 'Test User',
        'email' => 'test@gmail.com',
        'password' => bcrypt('password'),
    ]);

    Sanctum::actingAs($user);

    $task = Task::create([
        'title' => 'Task for mootez',
        'description' => 'task description',
        'status' => 'pending',
        'user_id' => $user->id,
    ]);

    SubTask::create([
        'task_id' => $task->id,
        'title' => 'Subtask 1',
        'description' => 'Subtask 1 description',
        'status' => 'pending'
    ]);

    SubTask::create([
        'task_id' => $task->id,
        'title' => 'Subtask 2',
        'description' => 'Subtask 2 description',
        'status' => 'done'
    ]);

    $response = $this->getJson('/api/tasks/' . $task->id . '/subtasks');
    $response->assertStatus(200);
    $response->assertJsonCount(2, 'subTasks');
});



it('can get subtasks by task id', function () {
    $user = User::create([
        'name' => 'Mootez Aouinti',
        'email' => 'mootez@gmail.com',
        'password' => bcrypt('123456'),
    ]);

    Sanctum::actingAs($user);

    $task = Task::create([
        'title' => 'Task for mootez',
        'description' => 'task description',
        'status' => 'pending',
        'user_id' => $user->id,
    ]);

    $subTask1 = SubTask::create([
        'task_id' => $task->id,
        'title' => 'Subtask 1',
        'description' => 'Subtask 1 description',
        'status' => 'pending',
    ]);

    $subTask2 = SubTask::create([
        'task_id' => $task->id,
        'title' => 'Subtask 2',
        'description' => 'Subtask 2 description',
        'status' => 'done',
    ]);

    $response = $this->getJson('/api/subtasks/' . $task->id);
    $response->assertStatus(200);
    $response->assertJsonFragment(['title' => 'Subtask 1']);
    $response->assertJsonFragment(['title' => 'Subtask 2']);
});

it('returns 404 if task id does not exist', function () {
    $user = User::create([
        'name' => 'Mootez Aouinti',
        'email' => 'mootez@gmail.com',
        'password' => bcrypt('123456'),
    ]);

    Sanctum::actingAs($user);

    $response = $this->getJson('/api/subtasks/9999');
    $response->assertStatus(404);
    $response->assertJsonFragment(['message' => 'There is no task with this ID']);
});

it('returns 404 if no subtasks are found for the task', function () {
    $user = User::create([
        'name' => 'Mootez Aouinti',
        'email' => 'mootez@gmail.com',
        'password' => bcrypt('123456'),
    ]);

    Sanctum::actingAs($user);

    $task = Task::create([
        'title' => 'Task without Subtasks',
        'description' => 'task description',
        'status' => 'pending',
        'user_id' => $user->id,
    ]);

    $response = $this->getJson('/api/subtasks/' . $task->id);
    $response->assertStatus(404);
    $response->assertJsonFragment(['message' => 'No SubTasks found for this task']);
});



