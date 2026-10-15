@extends('layouts.app')
@section('title', $staff->exists ? 'Edit Staff' : 'New Staff')
@section('content')
<div class="card shadow-sm">
  <div class="card-header">{{ $staff->exists ? 'Edit' : 'New' }} Staff</div>
  <div class="card-body">
    <form method="POST" action="{{ $staff->exists ? route('admin.staff.update', $staff) : route('admin.staff.store') }}">
      @csrf
      @if ($staff->exists) @method('PUT') @endif

      <div class="mb-3">
        <label class="form-label">Name</label>
        <input name="name" class="form-control" value="{{ old('name', $staff->name) }}" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" value="{{ old('email', $staff->email) }}" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Password {{ $staff->exists ? '(leave blank to keep)' : '' }}</label>
        <input type="password" name="password" class="form-control" {{ $staff->exists ? '' : 'required' }}>
      </div>
      <div class="form-check mb-2">
        <input type="checkbox" class="form-check-input" name="is_active" value="1" id="is_active" @checked(old('is_active', $staff->is_active ?? true))>
        <label class="form-check-label" for="is_active">Active</label>
      </div>
      <div class="form-check mb-3">
        <input type="checkbox" class="form-check-input" name="verified" value="1" id="verified" @checked(old('verified', (bool) $staff->email_verified_at))>
        <label class="form-check-label" for="verified">Verified</label>
      </div>
      <button class="btn btn-primary">Save</button>
      <a href="{{ route('admin.staff.index') }}" class="btn btn-link">Cancel</a>
    </form>
  </div>
</div>
@endsection