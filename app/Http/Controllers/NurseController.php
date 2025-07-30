<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Appointment;
use App\Models\PatientComplain;
use App\Models\Department;
use App\Models\NurseDetails;
use App\Models\FollowUp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;

class NurseController extends Controller
{
    public function dashboard()
    {
        $patients = User::where('user_role', 'patient')->get();
        $appointments = Appointment::orderBy('clients_date_and_time', 'asc')->take(5)->get();
        $followUps = FollowUp::where('nurse_id', Auth::id())
            ->where('status', 'pending')
            ->with(['patient', 'appointment'])
            ->orderBy('follow_up_date', 'asc')
            ->take(5)
            ->get();
        $alerts = PatientComplain::orderBy('created_at', 'desc')->take(3)->get();

        return Inertia::render('Nurse/Dashboard', [
            'patients' => $patients,
            'appointments' => $appointments,
            'followUps' => $followUps,
            'alerts' => $alerts,
        ]);
    }

    public function followUps()
    {
        return Inertia::render('Nurse/FollowUp', [
            'followUps' => FollowUp::where('nurse_id', Auth::id())
                ->with(['patient', 'appointment'])
                ->orderBy('follow_up_date', 'desc')
                ->get(),
            'completedAppointments' => Appointment::where('status', 'completed')
                ->with('patient')
                ->orderBy('clients_date_and_time', 'desc')
                ->get()
        ]);
    }

    public function storeFollowUp(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:users,id',
            'follow_up_date' => 'required|date|after:now',
            'notes' => 'required|string',
            'recommendations' => 'nullable|string'
        ]);

        $appointment = Appointment::where('user_id', $validated['patient_id'])
            ->where('status', 'completed')
            ->latest()
            ->first();

        if (!$appointment) {
            return redirect()->back()->with('error', 'No completed appointment found for this patient.');
        }

        $followUp = FollowUp::create([
            'appointment_id' => $appointment->id,
            'patient_id' => $validated['patient_id'],
            'nurse_id' => Auth::id(),
            'notes' => $validated['notes'],
            'recommendations' => $validated['recommendations'],
            'follow_up_date' => $validated['follow_up_date'],
            'status' => 'pending'
        ]);

        // Get the patient details
        $patient = User::find($validated['patient_id']);
        $nurse = Auth::user();

        // Send simple email to patient about the follow-up
        $subject = 'Follow-up Appointment Scheduled';
        $message = "Dear {$patient->surname} {$patient->otherNames},\n\n";
        $message .= "A follow-up has been scheduled for you by Nurse {$nurse->surname} {$nurse->otherNames}.\n\n";
        $message .= "Date and Time: " . date('F j, Y g:i A', strtotime($validated['follow_up_date'])) . "\n";
        $message .= "Notes: {$validated['notes']}\n";
        if ($validated['recommendations']) {
            $message .= "Recommendations: {$validated['recommendations']}\n";
        }
        $message .= "\nPlease log in to your dashboard to view more details.\n\n";
        $message .= "Best regards,\nTimeless Healthcare247";

        Mail::raw($message, function($mail) use ($patient, $subject) {
            $mail->to($patient->email)
                ->subject($subject);
        });

        return redirect()->back()->with('success', 'Follow-up scheduled successfully.');
    }

    public function updateFollowUp(Request $request, FollowUp $followUp)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,completed,missed',
            'action_taken' => 'required|string'
        ]);

        $followUp->update($validated);

        return redirect()->back()->with('success', 'Follow-up updated successfully.');
    }

    /**
     * Show the form for creating a new nurse.
     */
    public function create()
    {
        return Inertia::render('Admin/CreateNurse', [
            'departments' => Department::orderBy('id', 'DESC')->get(),
            'users' => User::where('user_role', '!=', 'nurse')
                ->where('user_role', '!=', 'doctor')
                ->where('user_role', '!=', 'admin')
                ->get(),
        ]);
    }

    /**
     * Store a newly created nurse in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'sentData.form.qualification' => 'required|string',
            'sentData.form.experience' => 'required|integer|min:0',
            'sentData.department' => 'required|exists:departments,id',
            'sentData.user' => 'required|exists:users,id',
        ]);

        // Create nurse details
        $newNurse = NurseDetails::create([
            'user_id' => $validatedData['sentData']['user'],
            'department' => $validatedData['sentData']['department'],
            'qualification' => $validatedData['sentData']['form']['qualification'],
            'years_of_experience' => $validatedData['sentData']['form']['experience'],
            'active' => true,
        ]);

        // Update user role
        $user = User::where('id', $validatedData['sentData']['user'])->first();
        $user->update([
            'user_role' => 'nurse',
        ]);

        return redirect()->back()->with('success', 'Nurse created successfully.');
    }
} 