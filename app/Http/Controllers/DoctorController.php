<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Doctor;
use App\Http\Requests\StoreDoctorRequest;
use App\Http\Requests\UpdateDoctorRequest;
use App\Models\DoctorDetails;
use App\Models\doctorRecommendation;
use App\Models\MedicalHistory;
use App\Models\MedicalRecords;
use App\Models\PatientComplain;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use inertia\inertia;
use PhpParser\Comment\Doc;

class DoctorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return inertia::render('Doctors/DoctorsDashboard', [

        ]);
    }

    public function fetchDepartmentMessages()
    {
        $doctor = DoctorDetails::where('user_id', auth()->id())->first();

        if ($doctor) {
            $complaints = PatientComplain::with('user')
                ->where('department_id', $doctor->department)
                ->get();
        } else {
            $complaints = [];
        }
        return inertia::render('Doctors/DepartmentMessages', [
            'complains' => $complaints
        ]);
    }

    public function addDoctor(Request $request)
    {
        $validatedData = $request->validate([
            'sentData.form.age' => 'int',
            'sentData.form.fee' => 'int',
            'sentData.form.qualification' => 'string',
            'sentData.department' => 'int',
            'sentData.user' => 'int',
        ]);

        $newDoctor = DoctorDetails::create([
            'user_id' => $validatedData['sentData']['user'],
            'age' => $validatedData['sentData']['form']['age'],
            'qualification' => $validatedData['sentData']['form']['qualification'],
            'fee' => $validatedData['sentData']['form']['fee'],
            'department' => $validatedData['sentData']['department'],
            'active' => true,
        ]);
        $newDoctor->save();
        $user = User::where('id', $validatedData['sentData']['user'])->first();

        $user->update([
            'user_role' => 'doctor',
        ]);

        $user->save();


        return response()->json(['message' => 'Doctor created successfully.']);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return inertia::render('Admin/CreateDoctor', [
            'departments' => Department::orderBy('id', 'DESC')->get(),
            'users' => User::all(),
        ]);
    }

    public function appointments()
    {
        return inertia::render('Doctors/Appointments', [

        ]);

    }

    public function saveComplainMedications(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'complaint_id' => 'required|exists:patient_complains,id',
            'complaints' => 'required|array',
            'complaints.*.complain' => 'required|string',
            'complaints.*.medication' => 'required|string',
            'complaints.*.dosage' => 'required|string',
        ]);


        $id = Auth::user()->id;
        $doctor = DoctorDetails::where('user_id', $id)->get();
        $doctor_id = $doctor[0]->id;


        foreach ($validatedData['complaints'] as $complaint) {
            $newMedication = doctorRecommendation::create([
                'patient_id' => $request->user_id,
                'doctor_id' => $doctor_id,
                'patient_complain_id' => $validatedData['complaint_id'],
                'complain' => $complaint['complain'],
                'medication' => $complaint['medication'] ?? '',
                'dosage' => $complaint['dosage'] ?? '',
            ]);
        }
    }


    public function createTakeALeave()
    {
        return inertia::render('Doctors/TakeALeave', [

        ]);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDoctorRequest $request)
    {

    }

    /**
     * Display the specified resource.
     */
    public function show(Doctor $doctor)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Doctor $doctor)
    {
        //
    }
    public function creatReplyMessage(PatientComplain $complain_id, User $complain_user_id)
    {
        $medicalHistory = MedicalHistory::where('user_id', $complain_user_id->id)->first();
        //        $medicalRecords = MedicalRecords::where('user_id', $complain_user_id->id)
//            ->with('doctor')
//            ->get();


        $medicalRecords = MedicalRecords::orderBy('created_at', 'DESC')
            ->where('user_id', $complain_user_id->id)
            ->with(['user', 'doctor.user']) // Ensure doctor relation loads user
            ->get()
            ->map(function ($record) {
                return [
                    'id' => $record->id,
                    'user_id' => $record->user_id,
                    'doctor_name' => $record->doctor->user->surname . ' ' . $record->doctor->user->otherNames ?? 'Unknown', // Extract doctor's name
                    'diagnosis' => $record->diagnosis,
                    'medications' => $record->medications,
                    'test_result' => $record->test_result,
                    'test_image' => $record->test_image,
                    'extra_notes' => $record->extra_notes,
                    'conducted_on' => $record->created_at->format('Y-m-d'),
                    'month' => $record->month,
                ];
            });



        return inertia::render('Doctors/ReplyPatientMessage', [
            'complain' => $complain_id,
            'complained_user' => $complain_user_id,
            'medicalHistory' => $medicalHistory ?? null, // Ensure it returns null if no record exists
            'medicalRecords' => $medicalRecords ?? null, // Ensure it returns null if no record exists
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDoctorRequest $request, Doctor $doctor)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Doctor $doctor)
    {
        //
    }
}
