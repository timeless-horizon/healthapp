<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class PatientFileShare extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_file_id',
        'patient_id',
        'doctor_id',
        'shared_at',
        'access_expires_at',
        'notes',
        'is_active'
    ];

    protected $casts = [
        'shared_at' => 'datetime',
        'access_expires_at' => 'datetime',
        'is_active' => 'boolean'
    ];

    // Relationship to the shared file
    public function patientFile()
    {
        return $this->belongsTo(PatientFile::class);
    }

    // Relationship to the patient who owns the file
    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    // Relationship to the doctor who has access
    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    // Check if access has expired
    public function getIsExpiredAttribute()
    {
        if (!$this->access_expires_at) {
            return false;
        }
        
        return Carbon::now()->isAfter($this->access_expires_at);
    }

    // Check if access is currently valid
    public function getIsValidAccessAttribute()
    {
        return $this->is_active && !$this->is_expired;
    }

    // Scope for active shares only
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope for non-expired shares
    public function scopeNotExpired($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('access_expires_at')
              ->orWhere('access_expires_at', '>', Carbon::now());
        });
    }

    // Scope for valid shares (active and not expired)
    public function scopeValid($query)
    {
        return $query->active()->notExpired();
    }
}
