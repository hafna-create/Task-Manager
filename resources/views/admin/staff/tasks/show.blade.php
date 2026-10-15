@extends('layouts.app')
@section('title', $task->title)
@section('content')
<div class="card shadow-sm">
  <div class="card-header d-flex justify-content-between">
    <span>{{ $task->title }}</span>
    <span class="badge bg-{{ $task->badgeClass() }}">{{ ucfirst(str_replace('_', ' ', $task->status)) }}</span>
  </div>
  <div class="card-body">
    <p><strong>Project:</strong> {{ $task->project->name }}</p>
    <p><strong>Due:</strong> {{ $task->due_date?->format('d M Y') ?? 'N/A' }}</p>
    <p>{{ $task->description }}</p>
    <hr>
    <form method="POST" action="{{ route('staff.tasks.status', $task) }}" class="row g-2">
      @csrf @method('PATCH')
      <div class="col-md-4">
        <select name="status" class="form-select">
          @foreach (App\Models\Task::STATUSES as $s)
            <option value="{{ $s }}" @selected($task->status === $s)>{{ ucfirst(str_replace('_', ' ', $s)) }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-auto"><button class="btn btn-primary">Update Status</button></div>
      <div class="col-auto"><a href="{{ route('staff.dashboard') }}" class="btn btn-link">Back</a></div>
    </form>
  </div>
</div>
@endsection