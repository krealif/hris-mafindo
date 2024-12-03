<?php

namespace App\Policies;

use App\Enums\RegistrationStepEnum;
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
     * Determine whether the user can view registration form.
     */
    public function viewForm(User $user, string $type): bool
    {
        if (
            $type == 'pengurus-wilayah'
            && !strpos($user->email, 'mafindo.or.id')
        ) {
            return false;
        }

        if ($regisType = $user->registration?->type) {
            return $regisType == $type;
        }

        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, string $type): bool
    {
        if (
            $type == 'pengurus-wilayah'
            && !strpos($user->email, 'mafindo.or.id')
        ) {
            return false;
        }

        if (
            $user->registration?->step
            && $user->registration?->step != RegistrationStepEnum::MENGISI->value
        ) {
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
