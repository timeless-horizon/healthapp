<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Appointment_Department_Doctor;
use App\Http\Requests\StoreAppointment_Department_DoctorRequest;
use App\Http\Requests\UpdateAppointment_Department_DoctorRequest;
use App\Models\DoctorDetails;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\AppointmentMail;


class AppointmentDepartmentDoctorController extends Controller
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

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Appointment $appointment_id)
    {
        // 1. Validate request
        $validatedAppointment = $request->validate([
            'doctorDateAndTime' => 'required|date',
            'meeting_link' => 'required|string',
            'user_id' => 'required|exists:users,id',
            'status' => 'string'
        ]);

        // 2. Get logged in doctor
        $loggedInUserId = Auth::user()->id;
        $doctor = DoctorDetails::where('user_id', $loggedInUserId)->first();

        if (!$doctor) {
            return response()->json(['error' => 'Doctor not found.'], 404);
        }

        $user = User::find($validatedAppointment['user_id']);
        if (!$user) {
            return response()->json(['error' => 'User not found.'], 404);
        }

        // 3. Create Appointment_Department_Doctor
        $appointmentDoctor = Appointment_Department_Doctor::create([
            'department_id' => $appointment_id->department_id,
            'appointment_id' => $appointment_id->id,
            'doctor_id' => $doctor->id,
            'available_data_and_time' => $validatedAppointment['doctorDateAndTime'],
            'meeting_link' => $validatedAppointment['meeting_link'],
            'status' => $validatedAppointment['status']
        ]);

        // 4. Update the appointment meeting link
        $appointment_id->update([
            'meeting_link' => $validatedAppointment['meeting_link'],
            'clients_date_and_time' => $validatedAppointment['doctorDateAndTime'],
            'status' => $validatedAppointment['status']
        ]);

        // Only send email if status is not "completed"
        if ($validatedAppointment['status'] == 'pending') {
            $formData = [
                'name' => $user->surname . ' ' . $user->otherNames,
                'doctorDateAndTime' => $validatedAppointment['doctorDateAndTime'],
                'meeting_link' => $validatedAppointment['meeting_link'],
            ];

            Mail::to($user->email)->send(new AppointmentMail($formData));
        }

        return response()->json([
            'message' => 'Appointment successfully created and updated.',
            'appointmentDoctor' => $appointmentDoctor,
            'appointment' => $appointment_id
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Appointment_Department_Doctor $appointment_Department_Doctor)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Appointment_Department_Doctor $appointment_Department_Doctor)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAppointment_Department_DoctorRequest $request, Appointment_Department_Doctor $appointment_Department_Doctor)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Appointment_Department_Doctor $appointment_Department_Doctor)
    {
        //
    }
}
