<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReferralCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'email',
        'name',
        'registrations_count',
        'clicks_count'
    ];

    public function incrementRegistrations()
    {
        $this->increment('registrations_count');
    }

    public function incrementClicks()
    {
        $this->increment('clicks_count');
    }
} 