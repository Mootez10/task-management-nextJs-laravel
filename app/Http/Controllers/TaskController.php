<?php

namespace App\Http\Controllers;

use App\Mail\TaskStatusUpdatedMail;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;

class TaskController extends Controller
{

    public function index()
    {
        try {
            $tasks = Task::where('user_id', auth()->id())->get();
            return response()->json($tasks);
        } catch (\Exception $e) {
            \Log::error('Error fetching tasks: ' . $e->getMessage());
            return response()->json(['error' => 'Something went wrong'], 500);
        }
    }


    public function store(Request $request)
    {
        $fields = $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
            'status' => 'nullable|string'

        ]);

        $task = $request->user()->tasks()->create($fields);

        return ['message' => 'Task saved successfully', 'task' => $task];
    }


    public function show($id)
    {
        try {
            $task = Task::findOrFail($id);

            return response()->json([
                'task' => $task
            ], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Task not found',
            ], 404);
        }
    }



    public function update(Request $request, $id)
    {

        $task = Task::find($id);
        if (!$task) {
            return response()->json(['message' => 'Task not found'], 404);
        }
        if (Gate::denies('modify', $task)) {
            return response()->json(['message' => 'You do not own this task'], 403);
        }
        $task->update($request->all());

        Mail::to('mootez@gmail.com')->send(new TaskStatusUpdatedMail($task, 'updated'));
        return response()->json($task, 200);
    }




    public function destroy(Request $request, $id)
    {
        $task = Task::find($id);

        if (!$task) {
            return response()->json(['message' => 'Task not found'], 404);
        }

        if ($task->user_id !== auth()->id()) {
            return response()->json(['message' => 'You do not have permission to delete this task'], 403);
        }

        $task->delete();

        Mail::to('mootez@gmail.com')->send(new TaskStatusUpdatedMail($task, 'deleted'));
        return response()->json(['message' => 'Task Deleted Successfully'], 200);
    }

    public function getTaskStat()
    {
        $userId = auth()->id();

        $pendingTasks = Task::where('user_id', $userId)->where('status', 'pending')->get();
        $doneTasks = Task::where('user_id', $userId)->where('status', 'done')->get();
        $canceledTasks = Task::where('user_id', $userId)->where('status', 'canceled')->get();

        $pendingCount = $pendingTasks->count();
        $doneCount = $doneTasks->count();
        $canceledCount = $canceledTasks->count();

        return response()->json([
            'pendingTasks' => [
                'count' => $pendingCount,
                'tasks' => $pendingTasks,
            ],
            'doneTasks' => [
                'count' => $doneCount,
                'tasks' => $doneTasks,
            ],
            'canceledTasks' => [
                'count' => $canceledCount,
                'tasks' => $canceledTasks,
            ],
        ]);
    }



}
