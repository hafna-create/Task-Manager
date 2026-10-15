@extends('layouts.app')
@section('title', 'Projects')
@section('content')
<div class="d-flex justify-content-between mb-3">
  <h4>Projects</h4>
  <a href="{{ route('admin.projects.create') }}" class="btn btn-primary">+ New Project</a>
</div>
<div class="card shadow-sm">
  <table class="table table-hover mb-0">
    <thead><tr><th>#</th><th>Name</th><th>Status</th><th>Tasks</th><th width="170">Actions</th></tr></thead>
    <tbody>
    @forelse ($projects as $p)
      <tr>
        <td>{{ $p->id }}</td>
        <td>{{ $p->name }}</td>
        <td><span class="badge bg-info">{{ ucfirst(str_replace('_', ' ', $p->status)) }}</span></td>
        <td>{{ $p->tasks_count }}</td>
        <td>
          <a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.projects.edit', $p) }}">Edit</a>
          <form class="d-inline" method="POST" action="{{ route('admin.projects.destroy', $p) }}" onsubmit="return confirm('Delete this project?')">
            @csrf @method('DELETE')
            <button class="btn btn-sm btn-outline-danger">Delete</button>
          </form>
        </td>
      </tr>
    @empty
      <tr><td colspan="5" class="text-center text-muted">No projects found.</td></tr>
    @endforelse
    </tbody>
  </table>
</div>
<div class="mt-3">{{ $projects->links() }}</div>
@endsection