<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Registration;
use App\Enums\RegistrationTypeEnum;
use App\Enums\RegistrationStatusEnum;
use App\Enums\RegistrationBaruStepEnum;
use App\Enums\RegistrationLamaStepEnum;

class RegistrationPolicy
{
    /**
     * Determine whether the user can view the registration form for a specific type.
     */
    public function viewForm(User $user, RegistrationTypeEnum $type): bool
    {
        if (
            $type == RegistrationTypeEnum::PENGURUS_WILAYAH
            && !strpos($user->email, 'mafindo.or.id')
        ) {
            return false;
        }

        if ($regisType = $user->registration?->type) {
            return $regisType == $type->value;
        }

        return true;
    }

    /**
     * Determine whether the user can create a registration for a specific type.
     */
    public function create(User $user, RegistrationTypeEnum $type): bool
    {
        if (
            $type == RegistrationTypeEnum::PENGURUS_WILAYAH
            && !strpos($user->email, 'mafindo.or.id')
        ) {
            return false;
        }

        if (
            $user->registration &&
            ($user->registration->status == RegistrationStatusEnum::DIPROSES->value
                || $user->registration->step != 'mengisi')
        ) {
            return false;
        }

        if ($regisType = $user->registration?->type) {
            return $regisType == $type->value;
        }

        return true;
    }

    /**
     * Determine whether the user can update the registration step.
     */
    public function updateStep(User $user, Registration $registration): bool
    {
        $disallowed = [
            RegistrationBaruStepEnum::MENGISI->value,
            RegistrationBaruStepEnum::PELATIHAN->value,
            RegistrationLamaStepEnum::VERIFIKASI->value
        ];

        return !in_array($registration->step, $disallowed);
    }

    /**
     * Determine whether the user can revise form data.
     */
    public function requestRevision(User $user, Registration $registration): bool
    {
        $allowed = [
            RegistrationBaruStepEnum::PROFILING->value,
            RegistrationLamaStepEnum::VERIFIKASI->value
        ];

        return in_array($registration->step, $allowed);
    }

    /**
     * Determine whether the user can finish the registration step.
     */
    public function finishStep(User $user, Registration $registration): bool
    {
        $allowed = [
            RegistrationBaruStepEnum::PELATIHAN->value,
            RegistrationLamaStepEnum::VERIFIKASI->value
        ];

        return in_array($registration->step, $allowed);
    }
}
