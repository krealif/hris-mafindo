<?php

namespace App\Policies;

use App\Models\Registration;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class RegistrationPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Registration $registration)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, string $type): bool
    {
        if (!strpos($user->email, 'mafindo.or.id')
            && $type == 'pengurus-wilayah') {
            return false;
        }

        if ($regisType = $user->registration?->type) {
            return $regisType == $type;
        }

        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Registration $registration)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Registration $registration)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Registration $registration)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Registration $registration)
    {
        //
    }
}
