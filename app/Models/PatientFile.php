<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class PatientFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'file_name',
        'original_file_name',
        'file_path',
        'file_size',
        'file_type',
        'purpose',
        'description',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'file_size' => 'integer'
    ];

    // Relationship to the patient who uploaded the file
    public function patient()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relationship to file shares
    public function shares()
    {
        return $this->hasMany(PatientFileShare::class);
    }

    // Get active shares only
    public function activeShares()
    {
        return $this->hasMany(PatientFileShare::class)->where('is_active', true);
    }

    // Get file size in human readable format
    public function getFormattedFileSizeAttribute()
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    // Get file download route (will be handled by controller)
    public function getDownloadUrlAttribute()
    {
        return route('patient.files.download', $this->id);
    }

    // Check if file is shared with a specific doctor
    public function isSharedWithDoctor($doctorId)
    {
        return $this->activeShares()
            ->where('doctor_id', $doctorId)
            ->exists();
    }
}
