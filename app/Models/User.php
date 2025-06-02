<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */

    // Define role constants
    public const ROLE_PATIENT = 'patient';
    public const ROLE_DOCTOR = 'doctor';
    public const ROLE_ADMIN = 'admin';

    protected $fillable = [
        'surname',
        'otherNames',
        'user_role',
        'address',
        'tel',
        'email',
        'medicalConditions',
        'medications',
        'username',
        'password',
        'preferredLanguage',
        'termsAccepted',
        'country',
        'state',
        'gender',
        'dateOfBirth'
    ];


    public function isAdmin()
    {
        return $this->user_role === self::ROLE_ADMIN;
    }

    public function isDoctor()
    {
        return $this->user_role === self::ROLE_DOCTOR;
    }

    public function isPatient()
    {
        return $this->user_role === self::ROLE_PATIENT;
    }


    public function currentPlan()
    {
        return $this->hasOne(UserPlan::class)->latestOfMany();
    }

    public function getPlanStatusAttribute()
    {
        return $this->currentPlan?->status;
    }


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
