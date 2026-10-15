<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\ProjectRequest;
use App\Models\Project;

class ProjectApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index() { return Project::withCount('tasks')->latest()->paginate(10); }
    public function store(ProjectRequest $request)
    {
        return response()->json($request->user()->projects()->create($request->validated()), 201);
    }
    public function show(Project $project) { return $project->load('tasks'); }
    public function update(ProjectRequest $request, Project $project)
    {
        $project->update($request->validated());
        return $project;
    }
    public function destroy(Project $project)
    {
        $project->delete();
        return response()->noContent();
    }
}
