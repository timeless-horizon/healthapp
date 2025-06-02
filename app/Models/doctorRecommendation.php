<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class doctorRecommendation extends Model
{
    /** @use HasFactory<\Database\Factories\DoctorRecommendationFactory> */
    use HasFactory;

    protected  $fillable = [
        'medication',
        'patient_id',
        'doctor_id',
        'dosage',
        'complain',
        'patient_complain_id'
    ];

    public function patientComplain()
    {
        return $this->belongsTo(PatientComplain::class, 'patient_complain_id');
    }
}
