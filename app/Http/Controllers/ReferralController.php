<?php

namespace App\Http\Controllers;

use App\Models\ReferralCode;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class ReferralController extends Controller
{
    public function index()
    {
        $referralCodes = ReferralCode::orderBy('created_at', 'desc')->get();
        return Inertia::render('Admin/ReferralSystem', [
            'referralCodes' => $referralCodes
        ]);
    }

    public function generateCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'name' => 'nullable|string|max:255'
        ]);

        $code = strtoupper(Str::random(8));
        
        $referralCode = ReferralCode::create([
            'code' => $code,
            'email' => $request->email,
            'name' => $request->name
        ]);

        return response()->json([
            'success' => true,
            'code' => $referralCode
        ]);
    }

    public function trackClick($code)
    {
        $referralCode = ReferralCode::where('code', $code)->first();
        
        if ($referralCode) {
            $referralCode->incrementClicks();
        }

        return redirect()->route('register', ['ref' => $code]);
    }

    public function destroy($id)
    {
        $referralCode = ReferralCode::findOrFail($id);
        $referralCode->delete();

        return response()->json([
            'message' => 'Referral code deleted successfully'
        ]);
    }
} 