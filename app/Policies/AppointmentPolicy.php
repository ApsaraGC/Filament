<?php

namespace App\Policies;

use App\Models\Appointment;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AppointmentPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user)
    {
        //
        return $user->role === 'admin' || $user->role === 'patient' || $user->role === 'doctor';
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Appointment $appointment): bool
    {
        //
        //return $user->role === 'admin' || $user->role === 'patient' || $user->role === 'doctor';
        // return $user->role === 'admin' ||
        // $user->role === 'patient' && $user->id === $appointment->patient_id ||
        // $user->role === 'doctor' && $user->id === $appointment->doctor_id;
        if ($user->role === 'patient') {
            return $user->patient->id === $appointment->patient_id;
        }
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        //
        // return $user->role === 'admin' || $user->role === 'patient';
        if ($user->role === 'patient') {
            return !empty($user->patient->id);
        }
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Appointment $appointment): bool
    {
        //
        return $user->role === 'admin' || $user->role === 'patient' && $user->id === $appointment->patient_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Appointment $appointment): bool
    {
        //
        return $user->role === 'admin' || $user->role === 'patient' && $user->id === $appointment->patient_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Appointment $appointment): bool
    {
        //
        return $user->role === 'admin' || $user->role === 'patient';
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Appointment $appointment): bool
    {
        //
        return $user->role === 'admin' || $user->role === 'patient';
    }
}
