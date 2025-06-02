<?php

namespace App\Http\Controllers;

use App\Models\doctorRecommendation;
use App\Models\PatientComplain;
use App\Models\PatientPrescription;
use App\Http\Requests\StorePatientPrescriptionRequest;
use App\Http\Requests\UpdatePatientPrescriptionRequest;
use Illuminate\Support\Facades\Auth;
use inertia\inertia;

class PatientPrescriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user_id = Auth::user()->id;
        return inertia::render('Patient/PatientPrescriptions', [
            'recomendations' => doctorRecommendation::orderBy('id', 'DESC')->where('patient_id', $user_id)
                ->with('patientComplain')->get(),

//            'complain' => PatientComplain::where('id', $complain_id)
//                ->where('user_id', $user_id)->first()->get(),
        ]);
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
    public function store(StorePatientPrescriptionRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(PatientPrescription $patientPrescription)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PatientPrescription $patientPrescription)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePatientPrescriptionRequest $request, PatientPrescription $patientPrescription)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PatientPrescription $patientPrescription)
    {
        //
    }
}
