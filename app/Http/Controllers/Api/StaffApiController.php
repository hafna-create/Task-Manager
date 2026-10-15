<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StaffRequest;
use App\Models\Staff;

class StaffApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() { return Staff::latest()->paginate(10); }
    public function store(StaffRequest $request) { return response()->json(Staff::create($request->payload()), 201); }
    public function update(StaffRequest $request, Staff $staff)
    {
        $staff->update($request->payload($staff));
        return $staff;
    }
    public function destroy(Staff $staff)
    {
        $staff->delete();
        return response()->noContent();
    }
}
