<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StaffRequest;
use App\Models\Staff;

class StaffManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $staff = Staff::latest()->paginate(10);
        return view('admin.staff.index', compact('staff'));
    }
    public function create() { return view('admin.staff.form', ['staff' => new Staff]); }
    public function store(StaffRequest $request)
    {
        Staff::create($request->payload());
        return redirect()->route('admin.staff.index')->with('success', 'Staff created.');
    }
    public function edit(Staff $staff) { return view('admin.staff.form', compact('staff')); }
    public function update(StaffRequest $request, Staff $staff)
    {
        $staff->update($request->payload($staff));
        return redirect()->route('admin.staff.index')->with('success', 'Staff updated.');
    }
    public function destroy(Staff $staff)
    {
        $staff->delete();
        return redirect()->route('admin.staff.index')->with('success', 'Staff deleted.');
    }
}
