<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ProjectRequest;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $projects = Project::withCount('tasks')->latest()->paginate(10);
        return view('admin.projects.index', compact('projects'));
    }
    public function create() { return view('admin.projects.form', ['project' => new Project]); }
    public function store(ProjectRequest $request)
    {
        Auth::guard('admin')->user()->projects()->create($request->validated());
        return redirect()->route('admin.projects.index')->with('success', 'Project created.');
    }
    public function edit(Project $project) { return view('admin.projects.form', compact('project')); }
    public function update(ProjectRequest $request, Project $project)
    {
        $project->update($request->validated());
        return redirect()->route('admin.projects.index')->with('success', 'Project updated.');
    }
    public function destroy(Project $project)
    {
        $project->delete();
        return redirect()->route('admin.projects.index')->with('success', 'Project deleted.');
    }

}
