@extends('layouts.app')
@section('title', $task->exists ? 'Edit Task' : 'New Task')
@section('content')
<div class="card shadow-sm">
  <div class="card-header">{{ $task->exists ? 'Edit' : 'New' }} Task</div>
  <div class="card-body">
    <form method="POST" action="{{ $task->exists ? route('admin.tasks.update', $task) : route('admin.tasks.store') }}">
      @csrf
      @if ($task->exists) @method('PUT') @endif

      <div class="row">
        <div class="col-md-6 mb-3">
          <label class="form-label">Project</label>
          <select name="project_id" class="form-select" required>
            <option value="">-- Select --</option>
            @foreach ($projects as $p)
              <option value="{{ $p->id }}" @selected(old('project_id', $task->project_id) == $p->id)>{{ $p->name }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-6 mb-3">
          <label class="form-label">Assign To</label>
          <select name="assigned_to" class="form-select">
            <option value="">-- Unassigned --</option>
            @foreach ($staffList as $s)
              <option value="{{ $s->id }}" @selected(old('assigned_to', $task->assigned_to) == $s->id)>{{ $s->name }}</option>
            @endforeach
          </select>
        </div>
      </div>
      <div class="mb-3">
        <label class="form-label">Title</label>
        <input name="title" class="form-control" value="{{ old('title', $task->title) }}" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Description</label>
        <textarea name="description" rows="3" class="form-control">{{ old('description', $task->description) }}</textarea>
      </div>
      <div class="row">
        <div class="col-md-6 mb-3">
          <label class="form-label">Status</label>
          <select name="status" class="form-select">
            @foreach (App\Models\Task::STATUSES as $s)
              <option value="{{ $s }}" @selected(old('status', $task->status ?? 'pending') === $s)>{{ ucfirst(str_replace('_', ' ', $s)) }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-6 mb-3">
          <label class="form-label">Due Date</label>
          <input type="date" name="due_date" class="form-control" value="{{ old('due_date', $task->due_date?->format('Y-m-d')) }}">
        </div>
      </div>
      <button class="btn btn-primary">Save</button>
      <a href="{{ route('admin.tasks.index') }}" class="btn btn-link">Cancel</a>
    </form>
  </div>
</div>
@endsection