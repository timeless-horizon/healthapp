<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    /** @use HasFactory<\Database\Factories\AppointmentFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'department_id',
        'reason',
        'problem',
        'clients_date_and_time',
        'meeting_link',
        "status",
    ];

    protected $with = ['patient'];

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function patient()
{
    return $this->belongsTo(User::class, 'user_id');
}

}
