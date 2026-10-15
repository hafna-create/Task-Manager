<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\StaffManagementController;
use App\Http\Controllers\StaffTaskController;
use App\Http\Controllers\TaskController;
use App\Models\Project;
use App\Models\Staff;
use App\Models\Task;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/staff/login');

/* ---------- ADMIN ---------- */
Route::prefix('admin')->name('admin.')->group(function () {

    Route::middleware('guest:admin')->group(function () {
        Route::get('login', [LoginController::class, 'show'])->name('login');
        Route::post('login', [LoginController::class, 'login'])->name('login.submit');
    });

    Route::middleware('auth:admin')->group(function () {
        Route::post('logout', [LoginController::class, 'logout'])->name('logout');

        Route::get('dashboard', function () {
            return view('admin.dashboard', [
                'projects'  => Project::count(),
                'staff'     => Staff::count(),
                'tasks'     => Task::count(),
                'completed' => Task::where('status', 'completed')->count(),

                'taskStatus' => Task::selectRaw('status, count(*) as c')->groupBy('status')->pluck('c', 'status'),

                'projectList' => Project::withCount([
                    'tasks',
                    'tasks as done_tasks_count' => fn ($q) => $q->where('status', 'completed'),
                ])->latest()->take(5)->get(),

                'recentTasks' => Task::with(['project', 'assignee'])->latest()->take(6)->get(),

                'staffStats' => [
                    'active'     => Staff::where('is_active', true)->whereNotNull('email_verified_at')->count(),
                    'inactive'   => Staff::where('is_active', false)->count(),
                    'unverified' => Staff::whereNull('email_verified_at')->count(),
                ],
            ]);
        })->name('dashboard');

        Route::resource('projects', ProjectController::class)->except('show');
        Route::resource('staff', StaffManagementController::class)->except('show');
        Route::resource('tasks', TaskController::class)->except('show');
    });
});

/* ---------- STAFF ---------- */
Route::prefix('staff')->name('staff.')->group(function () {
    Route::middleware('guest:staff')->group(function () {
        Route::get('login', [LoginController::class, 'show'])->name('login');
        Route::post('login', [LoginController::class, 'login'])->name('login.submit');
    });

    Route::post('logout', [LoginController::class, 'logout'])->middleware('auth:staff')->name('logout');

    Route::middleware(['auth:staff', 'staff.active'])->group(function () {
        Route::get('dashboard', [StaffTaskController::class, 'index'])->name('dashboard');
        Route::get('tasks/{task}', [StaffTaskController::class, 'show'])->name('tasks.show');
        Route::patch('tasks/{task}/status', [StaffTaskController::class, 'updateStatus'])->name('tasks.status');
    });
});