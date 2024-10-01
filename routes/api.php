<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\SubTaskController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('auth:api')->get('/authenticated-user', [UserController::class, 'getAuthenticatedUser']);
Route::middleware('auth:api')->put('/user/{id}', [UserController::class, 'updateUser']);
Route::middleware('auth:api')->delete('/user/{id}', [UserController::class, 'deleteUser']);


Route::middleware('auth:sanctum')->get('/tasks', [TaskController::class, 'index']) ;                                                               // hedhe bech ytaalali f list lkooool /api/tasks
Route::middleware('auth:sanctum')->post('/tasks', [TaskController::class, 'store']);
Route::middleware('auth:sanctum')->put('/tasks/{id}', [TaskController::class, 'update']);
Route::middleware('auth:sanctum')->delete('/tasks/{id}', [TaskController::class, 'destroy']);
Route::middleware('auth:api')->get('/tasks/{id}', [TaskController::class, 'show']);



Route::middleware('auth:api')->get('/subtasks/{task_id}', [SubTaskController::class, 'getSubtasksByTaskId']);
Route::middleware('auth:api')->post('/subtasks', [SubTaskController::class, 'store']);
Route::middleware('auth:api')->get('/subtasks', [SubTaskController::class, 'index']);
Route::middleware('auth:api')->get('/subtasks_id/{id}', [SubTaskController::class, 'show']);
Route::middleware('auth:api')->put('/subtasks/{id}', [SubTaskController::class, 'update']);
Route::middleware('auth:api')->delete('/subtasks/{id}', [SubTaskController::class, 'destroy']);

Route::middleware('auth:sanctum')->get('/statistics', action: [TaskController::class, 'getTaskStat']);



Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');                                                             //ken besh taamel request aal logout ykolik Unauthentificated kenek mekech mlogini asln 


Route::put('/user{id}', [UserController::class, 'updateUser']);
Route::delete('/user{id}', [UserController::class, 'deleteUser']);

