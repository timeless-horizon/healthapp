<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillingCycle extends Model
{
    /** @use HasFactory<\Database\Factories\BillingCycleFactory> */
    use HasFactory;

    protected $fillable = ['plans_id', 'duration', 'price'];

    // Relationships
    public function plan()
    {
        return $this->belongsTo(Plans::class);
    }

    public function userPlan()
    {
        return $this->hasMany(UserPlan::class);
    }
}
