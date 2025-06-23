<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ReferralCode;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Mail;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): Response
    {
        // Track referral code click if present in the request
        $refCode = null;
        if (request()->has('ref')) {
            $refCode = request()->ref;
            $referralCode = ReferralCode::where('code', $refCode)->first();
            if ($referralCode) {
                $referralCode->incrementClicks();
            }
        }

        return Inertia::render('Auth/Register', [
            'refCode' => $refCode
        ]);
    }

    private function getReferralEmailContent($referrerName, $newUserName, $totalRegistrations)
    {
        return <<<HTML
        <!DOCTYPE html>
        <html>
        <head>
            <title>New Referral Registration</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    line-height: 1.6;
                    color: #333;
                }
                .container {
                    max-width: 600px;
                    margin: 0 auto;
                    padding: 20px;
                }
                .header {
                    background-color: #0d9488;
                    color: white;
                    padding: 20px;
                    text-align: center;
                    border-radius: 5px 5px 0 0;
                }
                .content {
                    background-color: #f9fafb;
                    padding: 20px;
                    border-radius: 0 0 5px 5px;
                }
                .highlight {
                    color: #0d9488;
                    font-weight: bold;
                }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h1>New Referral Registration</h1>
                </div>
                <div class="content">
                    <p>Hello {$referrerName},</p>
                    
                    <p>Great news! Someone has just registered using your referral code.</p>
                    
                    <p>New user details:</p>
                    <ul>
                        <li>Name: <span class="highlight">{$newUserName}</span></li>
                    </ul>
                    
                    <p>Your referral code has now been used for a total of <span class="highlight">{$totalRegistrations}</span> registrations.</p>
                    
                    <p>Keep sharing your referral code to earn more rewards!</p>
                    
                    <p>Best regards,<br>
                    The Timeless Health Team</p>
                </div>
            </div>
        </body>
        </html>
        HTML;
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // Validate the request
        $validatedData = $request->validate([
            'surname' => 'required|string|max:255',
            'otherNames' => 'nullable|string|max:255',
            'address' => 'required|string|max:255',
            'tel' => 'required|string',
            'email' => 'required|string|email|max:255|unique:users,email',
            'medicalConditions' => 'nullable|string|max:500',
            'medications' => 'nullable|in:yes,no',
            'username' => 'required|string|max:255|unique:users,username',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'password_confirmation' => 'required|string',
            'preferredLanguage' => 'nullable|in:english,french,spanish',
            'termsAccepted' => 'accepted',
            'country' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'gender' => 'required|string|max:255',
            'dateOfBirth' => 'required|string|max:255',
            'ref' => 'nullable|string|exists:referral_codes,code'
        ]);

        // Create the user
        $user = User::create([
            'surname' => $validatedData['surname'],
            'otherNames' => $validatedData['otherNames'],
            'address' => $validatedData['address'],
            'tel' => $validatedData['tel'],
            'email' => $validatedData['email'],
            'medicalConditions' => $validatedData['medicalConditions'],
            'medications' => $validatedData['medications'],
            'username' => $validatedData['username'],
            'password' => Hash::make($validatedData['password']),
            'preferredLanguage' => $validatedData['preferredLanguage'],
            'termsAccepted' => $validatedData['termsAccepted'],
            'country' => $validatedData['country'],
            'state' => $validatedData['state'],
            'gender' => $validatedData['gender'],
            'dateOfBirth' => $validatedData['dateOfBirth'],
            'user_role' => 'patient',
        ]);

        // Handle referral code if present
        if ($request->has('ref')) {
            $referralCode = ReferralCode::where('code', $validatedData['ref'])->first();
            if ($referralCode) {
                $referralCode->incrementRegistrations();
                
                // Get the user who owns the referral code
                $referrer = User::find($referralCode->user_id);
                if ($referrer) {
                    // Get total registrations for this referral code
                    $totalRegistrations = $referralCode->registrations;
                    
                    // Send email notification with inline content
                    Mail::send([], [], function ($message) use ($referrer, $user, $totalRegistrations) {
                        $message->to($referrer->email)
                            ->subject('New Referral Registration')
                            ->html($this->getReferralEmailContent(
                                $referrer->name,
                                $user->name,
                                $totalRegistrations
                            ));
                    });
                }
            }
        }

        // Trigger the Registered event
        event(new Registered($user));

        // Log the user in
        Auth::login($user);

        // Redirect to the dashboard
        return redirect(route('dashboard'));
    }
}
