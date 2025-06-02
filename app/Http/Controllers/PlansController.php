<?php

namespace App\Http\Controllers;

use App\Models\Plans;
use App\Http\Requests\StorePlansRequest;
use App\Http\Requests\UpdatePlansRequest;
use App\Models\UserPlan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use inertia\Inertia;

class PlansController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (!Auth::check()) {

            //       return response()->json(["message" => "Please You need to login before you can purchase a plan"]);
            return inertia::render('Auth/Login', [
                'messege' => 'Please You need to login before you can purchase a plan'
            ]);
        } else {
            $userPlan = UserPlan::create([
                'user_id' => Auth::user()->id,
                'plans_id' => $request['plan']['id'],
                'cycle_id' => $request['plan']['billing_cycle'][0]['id'],
                'start_date' => Carbon::now()->toDateTimeString(),
                'end_date' => Carbon::now()->addMonth()->toDateString(),
                'status' => true,
            ]);
            return response()->json(["message" => "You've Successfully Purchase the" . $request['plan']['id'] . "plan"], 201);

        }


    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return inertia::render('Admin/CreatePlan', [


        ]);
    }

    public function availablePlans()
    {
        $user = auth()->user();
        // Fetch plans with their billing cycles and features
        $plans = Plans::with(['billingCycle', 'features'])->get();
        $plan_details = UserPlan::where('user_id', $user->id)
            ->with('plan', 'user')
            ->first();


        if ($plan_details && $plan_details->status === 1) {
            return inertia::render('Patient/ActivePlan', [
                'plan_details' => $plan_details,
            ]);
        } else {
            return inertia::render('Patient/AvailablePlans', [
                'plans' => $plans,
            ]);
        }

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request
        $validatedData = $request->validate([
            'form.title' => 'required|string|max:255',
            'form.description' => 'required|string',
            'form.price' => 'required|numeric|min:0',
            'form.features' => 'nullable|array',
            'form.features.*' => 'string|max:255',
        ]);

        // Create the Plan
//        dd($validatedData['form']['features']);
        $plan = Plans::create([
            'name' => $validatedData['form']['title'],
            'description' => $validatedData['form']['description'],
        ]);

        // Add a default billing cycle (optional)
        $plan->billingCycle()->create([
            'duration' => 1, // Default: 1 month
            'price' => $validatedData['form']['price'],
        ]);

        // Add Features
        if ($validatedData['form']['features']) {
            foreach ($validatedData['form']['features'] as $featureName) {
                $plan->features()->create([
                    'feature_name' => $featureName,
                ]);
            }
        }

        return response()->json(['message' => 'Plan created successfully'], 201);
    }


    /**
     * Display the specified resource.
     */
    public function show(Plans $plans)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Plans $plans)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePlansRequest $request, Plans $plans)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Plans $plans)
    {
        //
    }
}
