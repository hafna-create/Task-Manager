@extends('layouts.app')
@section('title', 'Admin Dashboard')
@section('content')

<div class="mb-4">
  <h4 class="mb-1">Welcome back, {{ auth('admin')->user()->name }}</h4>
  <p class="text-muted mb-0">Here is an overview of your projects, tasks and team.</p>
</div>

{{-- Summary cards --}}
<div class="row g-3 mb-4">
  @foreach ([['Projects', $projects], ['Staff', $staff], ['Tasks', $tasks], ['Completed', $completed]] as [$label, $count])
    <div class="col-6 col-md-3">
      <div class="card shadow-sm text-center"><div class="card-body">
        <h2>{{ $count }}</h2><p class="text-muted mb-0">{{ $label }}</p>
      </div></div>
    </div>
  @endforeach
</div>

<div class="row g-3 mb-4">
  {{-- Project progress --}}
  <div class="col-lg-7">
    <div class="card shadow-sm h-100">
      <div class="card-header d-flex justify-content-between align-items-center">
        <span>Project Progress</span>
        <a href="{{ route('admin.projects.index') }}" class="btn btn-sm btn-outline-secondary">View all</a>
      </div>
      <div class="card-body">
        @forelse ($projectList as $p)
          @php
            $percent = $p->tasks_count ? round($p->done_tasks_count / $p->tasks_count * 100) : 0;
            $badge = ['active' => 'success', 'on_hold' => 'warning', 'completed' => 'primary'][$p->status] ?? 'secondary';
          @endphp
          <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center mb-1">
              <strong>{{ $p->name }}</strong>
              <span class="badge bg-{{ $badge }}">{{ ucfirst(str_replace('_', ' ', $p->status)) }}</span>
            </div>
            <div class="progress" style="height: 8px;">
              <div class="progress-bar" role="progressbar" style="width: {{ $percent }}%"></div>
            </div>
            <small class="text-muted">{{ $p->done_tasks_count }} of {{ $p->tasks_count }} tasks completed ({{ $percent }}%)</small>
          </div>
        @empty
          <p class="text-muted mb-0">No projects yet.</p>
        @endforelse
      </div>
    </div>
  </div>

  {{-- Task status + staff overview --}}
  <div class="col-lg-5">
    <div class="card shadow-sm mb-3">
      <div class="card-header">Tasks by Status</div>
      <div class="card-body">
        @foreach (['pending' => 'warning', 'in_progress' => 'primary', 'completed' => 'success'] as $status => $color)
          @php $n = $taskStatus[$status] ?? 0; $pct = $tasks ? round($n / $tasks * 100) : 0; @endphp
          <div class="mb-3">
            <div class="d-flex justify-content-between">
              <span>{{ ucfirst(str_replace('_', ' ', $status)) }}</span>
              <strong>{{ $n }}</strong>
            </div>
            <div class="progress" style="height: 8px;">
              <div class="progress-bar bg-{{ $color }}" style="width: {{ $pct }}%"></div>
            </div>
          </div>
        @endforeach
      </div>
    </div>

    <div class="card shadow-sm">
      <div class="card-header">Staff Overview</div>
      <div class="card-body">
        <div class="d-flex justify-content-between mb-2">
          <span>Active and verified</span><span class="badge bg-success">{{ $staffStats['active'] }}</span>
        </div>
        <div class="d-flex justify-content-between mb-2">
          <span>Inactive</span><span class="badge bg-secondary">{{ $staffStats['inactive'] }}</span>
        </div>
        <div class="d-flex justify-content-between">
          <span>Not verified</span><span class="badge bg-warning">{{ $staffStats['unverified'] }}</span>
        </div>
      </div>
    </div>
  </div>
</div>

{{-- Recent tasks --}}
<div class="card shadow-sm">
  <div class="card-header d-flex justify-content-between align-items-center">
    <span>Recent Tasks</span>
    <a href="{{ route('admin.tasks.index') }}" class="btn btn-sm btn-outline-secondary">View all</a>
  </div>
  <div class="table-responsive">
    <table class="table table-hover mb-0">
      <thead>
        <tr><th>Title</th><th>Project</th><th>Assigned To</th><th>Status</th><th>Due</th></tr>
      </thead>
      <tbody>
      @forelse ($recentTasks as $t)
        <tr>
          <td>{{ $t->title }}</td>
          <td>{{ $t->project->name }}</td>
          <td>{{ $t->assignee?->name ?? 'Unassigned' }}</td>
          <td><span class="badge bg-{{ $t->badgeClass() }}">{{ ucfirst(str_replace('_', ' ', $t->status)) }}</span></td>
          <td>{{ $t->due_date?->format('d M Y') }}</td>
        </tr>
      @empty
        <tr><td colspan="5" class="text-center text-muted">No tasks yet.</td></tr>
      @endforelse
      </tbody>
    </table>
  </div>
</div>

@endsection