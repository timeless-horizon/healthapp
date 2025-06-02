<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalRecords extends Model
{
    /** @use HasFactory<\Database\Factories\MedicalRecordsFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'doctor_id',
        'diagnosis',
        'medications',
        'test_result',
        'test_image',
        'extra_notes',
        'month',
        'conducted_on',
    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function doctor()
    {
        return $this->belongsTo(DoctorDetails::class, 'doctor_id');
    }

}
