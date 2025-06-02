<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plans extends Model
{
    /** @use HasFactory<\Database\Factories\PlansFactory> */
    use HasFactory;


    protected $fillable = ['name', 'description'];

    // Relationships
    public function billingCycle()
    {
        return $this->hasMany(BillingCycle::class);
    }

    public function features()
    {
        return $this->hasMany(Features::class);
    }

    public function userPlans()
    {
        return $this->hasMany(UserPlan::class);
    }


}
