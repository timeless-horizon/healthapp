<?php

namespace App\Http\Controllers;

use App\Models\DoctorDetails;
use App\Models\Plans;
use App\Models\UserPlan;
use App\Http\Requests\StoreUserPlanRequest;
use App\Http\Requests\UpdateUserPlanRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class UserPlanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }


    public function getUserDashboardData()
    {

        $now = Carbon::now()->toDateString();
        $user = auth()->user();

        UserPlan::where('end_date', '<', $now)
            ->where('status', true)
            ->update(['status' => false]);

        $plan_details =  UserPlan::where('user_id', $user->id)
            ->with('plan', 'user')
            ->first();

            return response()->json([
                'plan_details' => $plan_details,
            ]);

    }
    public function checkPlanStatus()
    {
        $userId = Auth::user()->id;
       $userPlan = UserPlan::where('user_id', $userId)->where('status', false)->get();
       if ($userPlan){
           return $userPlan;
       }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserPlanRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(UserPlan $userPlan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(UserPlan $userPlan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserPlanRequest $request, UserPlan $userPlan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UserPlan $userPlan)
    {
        //
    }
}
