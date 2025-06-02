<?php

namespace App\Http\Controllers;

use App\Models\Features;
use App\Http\Requests\StoreFeaturesRequest;
use App\Http\Requests\UpdateFeaturesRequest;

class FeaturesController extends Controller
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
    public function store(StoreFeaturesRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Features $features)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Features $features)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFeaturesRequest $request, Features $features)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Features $features)
    {
        //
    }
}
