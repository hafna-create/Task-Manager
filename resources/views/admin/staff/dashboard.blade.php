@extends('layouts.app')
@section('title', 'My Tasks')
@section('content')
<h4 class="mb-3">My Tasks</h4>
<div class="row g-3 mb-4">
  @foreach (['pending' => 'warning', 'in_progress' => 'primary', 'completed' => 'success'] as $status => $color)
    <div class="col-md-4">
      <div class="card shadow-sm text-center border-{{ $color }}"><div class="card-body">
        <h2>{{ $counts[$status] ?? 0 }}</h2>
        <p class="text-muted mb-0">{{ ucfirst(str_replace('_', ' ', $status)) }}</p>
      </div></div>
    </div>
  @endforeach
</div>
<div class="card shadow-sm">
  <table class="table table-hover mb-0">
    <thead><tr><th>Title</th><th>Project</th><th>Status</th><th>Due</th><th></th></tr></thead>
    <tbody>
    @forelse ($tasks as $t)
      <tr>
        <td>{{ $t->title }}</td>
        <td>{{ $t->project->name }}</td>
        <td><span class="badge bg-{{ $t->badgeClass() }}">{{ ucfirst(str_replace('_', ' ', $t->status)) }}</span></td>
        <td>{{ $t->due_date?->format('d M Y') }}</td>
        <td><a href="{{ route('staff.tasks.show', $t) }}" class="btn btn-sm btn-outline-primary">View</a></td>
      </tr>
    @empty
      <tr><td colspan="5" class="text-center text-muted">No tasks assigned.</td></tr>
    @endforelse
    </tbody>
  </table>
</div>
<div class="mt-3">{{ $tasks->links() }}</div>
@endsection