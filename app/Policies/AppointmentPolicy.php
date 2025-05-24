<?php

namespace App\Policies;

use App\Models\Appointment;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AppointmentPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return true;
    }

    public function view(User $user, Appointment $appointment)
    {
        return $user->isAdmin() || $appointment->lawyers->contains($user->id);
    }

    public function create(User $user)
    {
        return $user->isAdmin();
    }

    public function update(User $user, Appointment $appointment)
    {
        return $user->isAdmin() || $appointment->lawyers->contains($user->id);
    }

    public function delete(User $user, Appointment $appointment)
    {
        return $user->isAdmin();
    }
}
