<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Http\Requests\StoreAdminRequest;
use App\Http\Requests\UpdateAdminRequest;
use App\Models\User;
use App\Models\Plans;
use App\Models\Appointment;
use App\Models\Department;
use Illuminate\Http\Request;

use Inertia\Inertia;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Admin/AddUser', []);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    public function addDepartment()
    {
        return Inertia::render('Admin/CreateDepartment', [
            'departments' => Department::orderBy('id', 'DESC')->get(),
        ]);
    }

    public function fetchDepartments()
    {
        return Department::orderBy('id', 'DESC')->get();
    }

    public function fetchAppointments()
    {
        return Inertia::render('Admin/allAppointment', [
            'appointments' => Appointment::orderBy('id', 'DESC')->get(),
        ]);
    }

    public function viewAllUsers()
    {
        return Inertia::render('Admin/allUsers', [
            'users' => User::orderBy(column: "id", direction: 'DESC')->get(),
        ]);
    }

    public function viewAllPlans()
    {
        return Inertia::render('Admin/allPlans', [
            'plans' => Plans::orderBy(column: "id", direction: 'DESC')->get(),
        ]);
    }

    public function storeDepartment(Request $request)
    {
        $validatedData = $request['form'];
        $department = Department::create([
            'name' => $validatedData['department'],
        ]);
        $department->save();
        return response()->json(['message' => 'Department saved successfully.']);
    }

    public function createAdmin()
    {
        return Inertia::render('Admin/CreateAdmin', [
            'users' => User::orderBy(column: "id", direction: 'DESC')->get(),
        ]);
    }

    public function makeUserAdmin(Request $request)
    {
        $userId = $request->input('user_id');

        $user = User::find($userId);

        if (!$user) {
            return response()->json(['message' => 'User not found.'], 404);
        }

        $user->user_role = 'admin';
        $user->save();

        return response()->json(['message' => 'User promoted to admin successfully.']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAdminRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Admin $admin)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Admin $admin)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAdminRequest $request, Admin $admin)
    {
        //
    }

    /**
     * Remove the specified plan from storage.
     */
    public function destroy(Plans $plan)
    {
        $plan->delete();
        return redirect()->back()->with('success', 'Plan deleted successfully.');
    }
    public function destroyUser(User $user)
    {
        $user->delete();
        return redirect()->back()->with('success', 'User deleted successfully.');
    }
    public function destroyDepartment(Department $department)
    {
        $department->delete();
        return redirect()->back()->with('success', 'Department deleted successfully.');
    }
}