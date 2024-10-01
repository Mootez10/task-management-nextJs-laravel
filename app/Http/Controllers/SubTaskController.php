<?php

namespace App\Http\Controllers;

use App\Mail\SubTaskStatusUpdatedMail;
use App\Models\SubTask;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class SubTaskController extends Controller
{
    public function index()
    {
        return SubTask::all();
    }

    public function store(Request $request)
    {
        $fields = $request->validate([
            'task_id' => 'required|exists:tasks,id',
            'title' => 'required|max:255',
            'description' => 'nullable|string',
            'status' => 'nullable|in:pending,done,canceled'
        ]);

        $subTask = SubTask::create($fields);

        return response()->json(['message' => 'SubTask created successfully', 'subTask' => $subTask], 201);
    }

    public function show($id)
    {
        try {
            $subTask = SubTask::findOrFail($id);
            return response()->json(['subTask' => $subTask], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => 'SubTask not found'], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $subTask = SubTask::find($id);
        if (!$subTask) {
            return response()->json(['message' => 'SubTask not found'], 404);
        }

        $fields = $request->validate([
            'title' => 'sometimes|required|max:255',
            'description' => 'nullable|string',
            'status' => 'nullable|in:pending,done,canceled'
        ]);

        $subTask->update($fields);
        Mail::to('mootez@gmail.com')->send(new SubTaskStatusUpdatedMail($subTask, 'updated'));

        return response()->json(['message' => 'SubTask updated successfully', 'subTask' => $subTask], 200);
    }

    public function destroy($id)
    {
        $subTask = SubTask::find($id);
        if (!$subTask) {
            return response()->json(['message' => 'SubTask not found'], 404);
        }

        $subTask->delete();
        Mail::to('mootez@gmail.com')->send(new SubTaskStatusUpdatedMail($subTask, 'deleted'));


        return response()->json(['message' => 'SubTask deleted successfully'], 200);
    }

    public function getSubtasksByTaskId($task_id)
    {
        $task = Task::find($task_id);
    
        if (!$task) {
            return response()->json(['message' => 'There is no task with this ID'], 404);
        }
    
        $subTasks = SubTask::where('task_id', $task_id)->get();
    
        if ($subTasks->isEmpty()) {
            return response()->json(['message' => 'No SubTasks found for this task'], 404);
        }
    
        return response()->json(['subTasks' => $subTasks], 200);
    }
    
}
