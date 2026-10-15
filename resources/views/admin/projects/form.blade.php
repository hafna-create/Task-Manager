@extends('layouts.app')
@section('title', $project->exists ? 'Edit Project' : 'New Project')
@section('content')
<div class="card shadow-sm">
  <div class="card-header">{{ $project->exists ? 'Edit' : 'New' }} Project</div>
  <div class="card-body">
    <form method="POST" action="{{ $project->exists ? route('admin.projects.update', $project) : route('admin.projects.store') }}">
      @csrf
      @if ($project->exists) @method('PUT') @endif

      <div class="mb-3">
        <label class="form-label">Name</label>
        <input name="name" class="form-control" value="{{ old('name', $project->name) }}" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Description</label>
        <textarea name="description" rows="3" class="form-control">{{ old('description', $project->description) }}</textarea>
      </div>
      <div class="mb-3">
        <label class="form-label">Status</label>
        <select name="status" class="form-select">
          @foreach (['active', 'on_hold', 'completed'] as $s)
            <option value="{{ $s }}" @selected(old('status', $project->status) === $s)>{{ ucfirst(str_replace('_', ' ', $s)) }}</option>
          @endforeach
        </select>
      </div>
      <button class="btn btn-primary">Save</button>
      <a href="{{ route('admin.projects.index') }}" class="btn btn-link">Cancel</a>
    </form>
  </div>
</div>
@endsection