<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\TaskRequest;
use App\Jobs\SendTaskAssignedNotification;
use App\Models\Task;

class TaskApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() { return Task::with(['project', 'assignee'])->latest()->paginate(10); }

    public function store(TaskRequest $request)
    {
        $task = $request->user()->tasks()->create($request->validated());
        if ($task->assigned_to) SendTaskAssignedNotification::dispatch($task);
        return response()->json($task->load(['project', 'assignee']), 201);
    }

    public function show(Task $task) { return $task->load(['project', 'assignee']); }

    public function update(TaskRequest $request, Task $task)
    {
        $task->fill($request->validated());
        $reassigned = $task->isDirty('assigned_to');
        $task->save();
        if ($reassigned && $task->assigned_to) SendTaskAssignedNotification::dispatch($task);
        return $task->load(['project', 'assignee']);
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return response()->noContent();
    }
}
