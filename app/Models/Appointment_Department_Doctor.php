<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment_Department_Doctor extends Model
{
    /** @use HasFactory<\Database\Factories\AppointmentDepartmentDoctorFactory> */
    use HasFactory;


    protected $fillable = [
        'department_id',
        'doctor_id',
        'available_data_and_time',
        'appointment_id',
        'meeting_link',
        "status",
    ];
}
