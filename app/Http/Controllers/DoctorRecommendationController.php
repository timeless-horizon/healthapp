<?php

namespace App\Http\Controllers;

use App\Models\doctorRecommendation;
use App\Http\Requests\StoredoctorRecommendationRequest;
use App\Http\Requests\UpdatedoctorRecommendationRequest;

class DoctorRecommendationController extends Controller
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
    public function store(StoredoctorRecommendationRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(doctorRecommendation $doctorRecommendation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(doctorRecommendation $doctorRecommendation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatedoctorRecommendationRequest $request, doctorRecommendation $doctorRecommendation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(doctorRecommendation $doctorRecommendation)
    {
        //
    }
}
