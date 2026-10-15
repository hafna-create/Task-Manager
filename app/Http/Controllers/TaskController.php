<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\TaskRequest;
use App\Jobs\SendTaskAssignedNotification;
use App\Models\Project;
use App\Models\Staff;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;


class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tasks = Task::with(['project', 'assignee'])->latest()->paginate(10);
        return view('admin.tasks.index', compact('tasks'));
    }

    private function formData(Task $task): array
    {
        return [
            'task' => $task,
            'projects' => Project::orderBy('name')->get(),
            'staffList' => Staff::where('is_active', true)->whereNotNull('email_verified_at')->orderBy('name')->get(),
        ];
    }

    public function create() { return view('admin.tasks.form', $this->formData(new Task)); }
    public function edit(Task $task) { return view('admin.tasks.form', $this->formData($task)); }

    public function store(TaskRequest $request)
    {
        $task = Auth::guard('admin')->user()->tasks()->create($request->validated());

        if ($task->assigned_to) {
            SendTaskAssignedNotification::dispatch($task);   // runs in background queue
        }
        return redirect()->route('admin.tasks.index')->with('success', 'Task created.');
    }

    public function update(TaskRequest $request, Task $task)
    {
        $task->fill($request->validated());
        $reassigned = $task->isDirty('assigned_to');
        $task->save();

        if ($reassigned && $task->assigned_to) {
            SendTaskAssignedNotification::dispatch($task);
        }
        return redirect()->route('admin.tasks.index')->with('success', 'Task updated.');
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return redirect()->route('admin.tasks.index')->with('success', 'Task deleted.');
    }
}
