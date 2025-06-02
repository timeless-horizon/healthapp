<?php

namespace App\Policies;

use App\Models\Appointment_Department_Doctor;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AppointmentDepartmentDoctorPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Appointment_Department_Doctor $appointmentDepartmentDoctor): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Appointment_Department_Doctor $appointmentDepartmentDoctor): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Appointment_Department_Doctor $appointmentDepartmentDoctor): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Appointment_Department_Doctor $appointmentDepartmentDoctor): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Appointment_Department_Doctor $appointmentDepartmentDoctor): bool
    {
        return false;
    }
}
