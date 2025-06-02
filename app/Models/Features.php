<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Features extends Model
{
    /** @use HasFactory<\Database\Factories\FeaturesFactory> */
    use HasFactory;

    protected $fillable = ['plans_id', 'feature_name'];

    // Relationships
    public function plan()
    {
        return $this->belongsTo(Plans::class);
    }



}
