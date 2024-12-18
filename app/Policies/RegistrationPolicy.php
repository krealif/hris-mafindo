<?php

namespace App\Policies;

use App\Enums\RegistrationBaruStepEnum;
use App\Enums\RegistrationLamaStepEnum;
use App\Enums\RegistrationStatusEnum;
use App\Enums\RegistrationTypeEnum;
use App\Models\Registration;
use App\Models\User;

class RegistrationPolicy
{
    /**
     * Determine whether the user can view the registration form for a specific type.
     */
    public function viewForm(User $user, RegistrationTypeEnum $type): bool
    {
        if (
            $type == RegistrationTypeEnum::PENGURUS_WILAYAH
            && ! strpos($user->email, 'mafindo.or.id')
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
            && ! strpos($user->email, 'mafindo.or.id')
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

    /*
    |--------------------------------------------------------------------------
    | Admin (used in RegistrationSubmissionController)
    |--------------------------------------------------------------------------
    */

    /**
     * Determine whether the admin can update the registration step.
     */
    public function nextStep(User $user, Registration $registration): bool
    {
        return $registration->status == RegistrationStatusEnum::DIPROSES->value
            && in_array($registration->step, [
                RegistrationBaruStepEnum::PROFILING->value,
                RegistrationBaruStepEnum::WAWANCARA->value,
                RegistrationBaruStepEnum::TERHUBUNG->value,
            ]);
    }

    /**
     * Determine whether the admin can revise form data.
     */
    public function requestRevision(User $user, Registration $registration): bool
    {
        return $registration->status == RegistrationStatusEnum::DIPROSES->value
            && in_array($registration->step, [
                RegistrationBaruStepEnum::PROFILING->value,
                RegistrationLamaStepEnum::VERIFIKASI->value,
            ]);
    }

    /**
     * Determine whether the admin can finish the registration step.
     */
    public function finish(User $user, Registration $registration): bool
    {
        return $registration->status == RegistrationStatusEnum::DIPROSES->value
            && in_array($registration->step, [
                RegistrationBaruStepEnum::PELATIHAN->value,
                RegistrationLamaStepEnum::VERIFIKASI->value,
            ]);
    }

    /**
     * Determine whether the admin can reject the registration.
     */
    public function reject(User $user, Registration $registration): bool
    {
        return $registration->status == RegistrationStatusEnum::DIPROSES->value
            && in_array($registration->step, [
                RegistrationBaruStepEnum::PROFILING->value,
                RegistrationBaruStepEnum::WAWANCARA->value,
                RegistrationLamaStepEnum::VERIFIKASI->value,
            ]);
    }

    /**
     * Determine whether the admin can destroy the registration.
     */
    public function destroy(User $user, Registration $registration): bool
    {
        if (
            (in_array($registration->status, ['draft', 'revisi'])
                && $registration->updated_at?->diffInDays() >= 7)
            || $registration->status == RegistrationStatusEnum::DITOLAK->value
        ) {
            return true;
        }

        return false;
    }
}
