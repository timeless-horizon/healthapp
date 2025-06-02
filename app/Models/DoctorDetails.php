<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoctorDetails extends Model
{
    protected $fillable = [
            'user_id',
            'age',
            'qualification',
            'fee',
            'department',
            'active',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

}
