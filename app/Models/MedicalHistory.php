<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalHistory extends Model
{
    /** @use HasFactory<\Database\Factories\MedicalHistoryFactory> */
    use HasFactory;


    protected $fillable = [
        'medical_condition',
        'messege',
        'user_id',
        'medications',
        'allergies',
        'family_medical_history',
        'previous_surgeries_or_hospitalizations',
    ];
}
