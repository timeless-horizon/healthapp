<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Appointment;
use App\Models\PatientComplain;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class NurseController extends Controller
{
    public function dashboard()
    {
        // Example: Fetch patients, appointments, tasks, alerts, etc.
        $patients = User::where('user_role', 'patient')->get();
        $appointments = Appointment::orderBy('clients_date_and_time', 'asc')->take(5)->get();
        $followUps = Appointment::where('status', 'attended')->take(5)->get();
        $alerts = PatientComplain::orderBy('created_at', 'desc')->take(3)->get();

        return Inertia::render('Nurse/Dashboard', [
            'patients' => $patients,
            'appointments' => $appointments,
            'followUps' => $followUps,
            'alerts' => $alerts,
            // Add more as needed
        ]);
    }
} 