<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
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
} 