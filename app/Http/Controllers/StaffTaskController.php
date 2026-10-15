<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UpdateTaskStatusRequest;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;

class StaffTaskController extends Controller
{
    private function authorizeTask(Task $task): void
    {
        abort_unless($task->assigned_to === Auth::guard('staff')->id(), 403);
    }

    public function index()
    {
        $staff = Auth::guard('staff')->user();
        $tasks = $staff->tasks()->with('project')->latest()->paginate(10);
        $counts = $staff->tasks()->selectRaw('status, count(*) as c')->groupBy('status')->pluck('c', 'status');
        return view('staff.dashboard', compact('tasks', 'counts'));
    }

    public function show(Task $task)
    {
        $this->authorizeTask($task);
        $task->load('project');
        return view('staff.tasks.show', compact('task'));
    }

    public function updateStatus(UpdateTaskStatusRequest $request, Task $task)
    {
        $this->authorizeTask($task);
        $task->update(['status' => $request->status]);
        return back()->with('success', 'Status updated.');
    }
}
