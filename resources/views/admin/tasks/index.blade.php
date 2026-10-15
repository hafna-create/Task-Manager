@extends('layouts.app')
@section('title', 'Tasks')
@section('content')
<div class="d-flex justify-content-between mb-3">
  <h4>Tasks</h4>
  <a href="{{ route('admin.tasks.create') }}" class="btn btn-primary">+ New Task</a>
</div>
<div class="card shadow-sm">
  <table class="table table-hover mb-0">
    <thead><tr><th>#</th><th>Title</th><th>Project</th><th>Assigned To</th><th>Status</th><th>Due</th><th width="170">Actions</th></tr></thead>
    <tbody>
    @forelse ($tasks as $t)
      <tr>
        <td>{{ $t->id }}</td>
        <td>{{ $t->title }}</td>
        <td>{{ $t->project->name }}</td>
        <td>{{ $t->assignee?->name ?? 'Unassigned' }}</td>
        <td><span class="badge bg-{{ $t->badgeClass() }}">{{ ucfirst(str_replace('_', ' ', $t->status)) }}</span></td>
        <td>{{ $t->due_date?->format('d M Y') }}</td>
        <td>
          <a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.tasks.edit', $t) }}">Edit</a>
          <form class="d-inline" method="POST" action="{{ route('admin.tasks.destroy', $t) }}" onsubmit="return confirm('Delete this task?')">
            @csrf @method('DELETE')
            <button class="btn btn-sm btn-outline-danger">Delete</button>
          </form>
        </td>
      </tr>
    @empty
      <tr><td colspan="7" class="text-center text-muted">No tasks found.</td></tr>
    @endforelse
    </tbody>
  </table>
</div>
<div class="mt-3">{{ $tasks->links() }}</div>
@endsection