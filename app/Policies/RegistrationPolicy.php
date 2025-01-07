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
        // Tolak akses ke form PENGURUS_WILAYAH jika emailnya bukan mafindo.or.id
        if (
            $type == RegistrationTypeEnum::PENGURUS_WILAYAH
            && ! strpos($user->email, 'mafindo.or.id')
        ) {
            return false;
        }

        $registration = $user->registration;

        // Izinkan melihat formulir jika belum ada data
        if (! $registration?->type) {
            return true;
        }

        // Izinkan jika tipe registrasi pengguna cocok dengan tipe formulir
        return $registration->type == $type;
    }

    /**
     * Determine whether the user can create a registration for a specific type.
     */
    public function create(User $user, RegistrationTypeEnum $type): bool
    {
        // Tolak menyimpan registrasi PENGURUS_WILAYAH jika email bukan mafindo.or.id
        if (
            $type == RegistrationTypeEnum::PENGURUS_WILAYAH
            && ! strpos($user->email, 'mafindo.or.id')
        ) {
            return false;
        }

        $registration = $user->registration;

        // Izinkan menyimpan registrasi untuk pertama kali
        if (! $registration?->type) {
            return true;
        }

        // Izinkan saat langkah registrasi 'MENGISI' dan statusnya draft atau revisi
        if (
            $registration->step->value == 'mengisi'
            && in_array($registration->status, [
                RegistrationStatusEnum::DRAFT,
                RegistrationStatusEnum::REVISI,
            ])
        ) {
            return $registration->type == $type;
        }

        return false;
    }

    /*
    |--------------------------------------------------------------------------
    | Admin (used in RegistrationReviewController)
    |--------------------------------------------------------------------------
    */

    /**
     * Determine whether the admin can update the registration step.
     */
    public function nextStep(User $user, Registration $registration): bool
    {
        return $registration->status == RegistrationStatusEnum::DIPROSES
            && in_array($registration->step, [
                RegistrationBaruStepEnum::PROFILING,
                RegistrationBaruStepEnum::WAWANCARA,
                RegistrationBaruStepEnum::TERHUBUNG,
            ]);
    }

    /**
     * Determine whether the admin can revise form data.
     */
    public function requestRevision(User $user, Registration $registration): bool
    {
        return $registration->status == RegistrationStatusEnum::DIPROSES
            && in_array($registration->step, [
                RegistrationBaruStepEnum::PROFILING,
                RegistrationLamaStepEnum::VERIFIKASI,
            ]);
    }

    /**
     * Determine whether the admin can approve the registration step.
     */
    public function approve(User $user, Registration $registration): bool
    {
        return $registration->status == RegistrationStatusEnum::DIPROSES
            && in_array($registration->step, [
                RegistrationBaruStepEnum::PELATIHAN,
                RegistrationLamaStepEnum::VERIFIKASI,
            ]);
    }

    /**
     * Determine whether the admin can reject the registration.
     */
    public function reject(User $user, Registration $registration): bool
    {
        return $registration->status == RegistrationStatusEnum::DIPROSES
            && in_array($registration->step, [
                RegistrationBaruStepEnum::PROFILING,
                RegistrationBaruStepEnum::WAWANCARA,
                RegistrationLamaStepEnum::VERIFIKASI,
            ]);
    }

    /**
     * Determine whether the admin can destroy the registration.
     */
    public function destroy(User $user, Registration $registration): bool
    {
        // Izinkan penghapusan jika statusnya 'DITOLAK'
        // Izinkan penghapusan 7 hari sejak pembaruan terakhir jika statusnya adalah 'DRAFT' atau 'REVISI'
        if (
            $registration->status == RegistrationStatusEnum::DITOLAK
            || (in_array($registration->status, [
                RegistrationStatusEnum::DRAFT,
                RegistrationStatusEnum::REVISI,
            ]) && $registration->updated_at?->diffInDays() >= 7)
        ) {
            return true;
        }

        return false;
    }
}
