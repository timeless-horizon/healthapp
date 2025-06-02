<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPlan extends Model
{
    /** @use HasFactory<\Database\Factories\UserPlanFactory> */
    use HasFactory;

    protected $fillable = ['user_id', 'plans_id', 'duration', 'start_date', 'end_date', 'status'];






    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function plan()
    {
        return $this->belongsTo(Plans::class, 'plans_id');
    }

    public function billingCycle()
    {
        return $this->belongsTo(BillingCycle::class, 'cycle_id');
    }

}
