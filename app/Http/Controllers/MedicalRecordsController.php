<?php

namespace App\Http\Controllers;

use App\Models\DoctorDetails;
use App\Models\MedicalRecords;
use App\Http\Requests\StoreMedicalRecordsRequest;
use App\Http\Requests\UpdateMedicalRecordsRequest;
use App\Models\User;
use Faker\Provider\Medical;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use inertia\inertia;

class MedicalRecordsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return inertia::render('Doctors/MedicalRecord', [
            'records' => MedicalRecords::orderBy('created_at', 'DESC')
                ->where('doctor_id', Auth::user()->id)
                ->with('user')
                ->get(),
        ]);

    }

    public function userRecords()
    {
        return inertia::render('Patient/MedicalRecords', [
            'records' => MedicalRecords::orderBy('created_at', 'DESC')
                ->where('user_id', Auth::user()->id)
                ->with(['user', 'doctor.user']) // Ensure doctor relation loads user
                ->get()
                ->map(function ($record) {
                    return [
                        'id' => $record->id,
                        'user_id' => $record->user_id,
                        'doctor_name' => $record->doctor->user->surname. ' '. $record->doctor->user->otherNames?? 'Unknown', // Extract doctor's name
                        'diagnosis' => $record->diagnosis,
                        'medications' => $record->medications,
                        'test_result' => $record->test_result,
                        'test_image' => $record->test_image,
                        'extra_notes' => $record->extra_notes,
                        'conducted_on' => $record->created_at->format('Y-m-d'),
                        'month' => $record->month,
                    ];
                }),
        ]);

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return inertia::render('Doctors/CreateMedicalRecord', [
            'users' => User::all(),
        ]);
    }
    public function viewUserRecord(User $user_id, MedicalRecords $record_id)
    {
        $viewedRecord = MedicalRecords::where('id', $record_id->id)->where('user_id', $user_id->id)->first();

//dd($viewedRecord);
        return inertia::render('Doctors/ViewPatientRecord', [
            'record' => MedicalRecords::where('user_id', $user_id->id)
                ->where('id', $record_id->id)
                ->with(['user', 'doctor.user']) // Ensure doctor relation loads user
                ->get()
                ->map(function ($record) {
                    return [
                        'id' => $record->id,
                        'user_id' => $record->user_id,
                        'doctor_name' => $record->doctor->user->surname. ' '. $record->doctor->user->otherNames?? 'Unknown', // Extract doctor's name
                        'diagnosis' => $record->diagnosis,
                        'medications' => $record->medications,
                        'test_result' => $record->test_result,
                        'test_image' => $record->test_image,
                        'extra_notes' => $record->extra_notes,
                        'conducted_on' => $record->created_at->format('Y-m-d'),
                        'month' => $record->month,
                    ];
                }),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validatedData = $request->validate([
            'user_id'     => 'required|exists:users,id',
//            'doctor_id'   => 'required|exists:doctor_details,id',
            'diagnosis'   => 'required|string',
            'conducted_on'   => 'required|date',
            'medications' => 'nullable|string',
            'test_result' => 'required|string',
            'test_image'  => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'extra_notes' => 'nullable|string',
        ]);


        $doctor = DoctorDetails::where('user_id', Auth::user()->id)->first();
        $validatedData['doctor_id'] = $doctor->id;
//        dd($doctor);

        if ($request->hasFile('test_image')) {
            $validatedData['test_image'] = $request->file('test_image')->store('test_images', 'public');
        }


        $validatedData['month'] = $request->input('month', now()->format('F'));

        $record = MedicalRecords::create($validatedData);

        return response()->json([
            'message' => 'Medical record saved successfully',
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(MedicalRecords $medicalRecords)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MedicalRecords $medicalRecords)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMedicalRecordsRequest $request, MedicalRecords $medicalRecords)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MedicalRecords $medicalRecords)
    {
        //
    }
}
