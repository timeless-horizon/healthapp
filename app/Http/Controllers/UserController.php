<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'surname' => ['required', 'string', 'max:255'],
            'otherNames' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:8'],
        ]);

        $user->surname = $validated['surname'];
        $user->otherNames = $validated['otherNames'];
        $user->email = $validated['email'];

        // Only update password if a new one is provided
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return back()->with('success', 'User updated successfully');
    }

    public function assignPlan(Request $request)
    {
        try {
            Log::info('Assigning plan request:', $request->all());

            $validated = $request->validate([
                'userId' => 'required|exists:users,id',
                'planId' => 'required|exists:plans,id',
                'duration' => 'required|integer|min:1'
            ]);

            Log::info('Validated data:', $validated);

            // Check if user already has an active plan
            $existingPlan = UserPlan::where('user_id', $request->userId)
                ->where('status', 1)
                ->first();

            if ($existingPlan) {
                // Update existing plan
                $existingPlan->update([
                    'plans_id' => $request->planId,
                    'duration' => $request->duration,
                    'status' => 1,
                    'start_date' => now(),
                    'end_date' => now()->addMonths($request->duration)
                ]);

                Log::info('Plan updated successfully', ['user_plan' => $existingPlan]);
                return redirect()->back()->with('success', 'Plan updated successfully');
            }

            // Create new user plan
            $userPlan = UserPlan::create([
                'user_id' => $request->userId,
                'plans_id' => $request->planId,
                'duration' => $request->duration,
                'status' => 1,
                'start_date' => now(),
                'end_date' => now()->addMonths($request->duration)
            ]);

            Log::info('Plan assigned successfully', ['user_plan' => $userPlan]);
            return redirect()->back()->with('success', 'Plan assigned successfully');

        } catch (\Exception $e) {
            Log::error('Error assigning plan: ' . $e->getMessage(), [
                'exception' => $e,
                'request_data' => $request->all()
            ]);
            
            return redirect()->back()->with('error', 'Failed to assign plan: ' . $e->getMessage());
        }
    }
} 