@extends('layouts.app')
@section('title', 'Staff')
@section('content')
<div class="d-flex justify-content-between mb-3">
  <h4>Staff Members</h4>
  <a href="{{ route('admin.staff.create') }}" class="btn btn-primary">+ New Staff</a>
</div>
<div class="card shadow-sm">
  <table class="table table-hover mb-0">
    <thead><tr><th>#</th><th>Name</th><th>Email</th><th>Active</th><th>Verified</th><th width="170">Actions</th></tr></thead>
    <tbody>
    @forelse ($staff as $s)
      <tr>
        <td>{{ $s->id }}</td>
        <td>{{ $s->name }}</td>
        <td>{{ $s->email }}</td>
        <td><span class="badge bg-{{ $s->is_active ? 'success' : 'secondary' }}">{{ $s->is_active ? 'Yes' : 'No' }}</span></td>
        <td><span class="badge bg-{{ $s->email_verified_at ? 'success' : 'warning' }}">{{ $s->email_verified_at ? 'Yes' : 'No' }}</span></td>
        <td>
          <a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.staff.edit', $s) }}">Edit</a>
          <form class="d-inline" method="POST" action="{{ route('admin.staff.destroy', $s) }}" onsubmit="return confirm('Delete this staff member?')">
            @csrf @method('DELETE')
            <button class="btn btn-sm btn-outline-danger">Delete</button>
          </form>
        </td>
      </tr>
    @empty
      <tr><td colspan="6" class="text-center text-muted">No staff found.</td></tr>
    @endforelse
    </tbody>
  </table>
</div>
<div class="mt-3">{{ $staff->links() }}</div>
@endsection