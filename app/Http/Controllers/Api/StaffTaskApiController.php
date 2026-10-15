<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateTaskStatusRequest;
use App\Models\Task;

class StaffTaskApiController extends Controller
{
    public function index(Request $request)
    {
        return $request->user()->tasks()->with('project')->latest()->paginate(10);
    }

    public function show(Request $request, Task $task)
    {
        abort_unless($task->assigned_to === $request->user()->id, 403);
        return $task->load('project');
    }

    public function updateStatus(UpdateTaskStatusRequest $request, Task $task)
    {
        abort_unless($task->assigned_to === $request->user()->id, 403);
        $task->update(['status' => $request->status]);
        return response()->json(['message' => 'Status updated.', 'task' => $task]);
    }
}
