<?php

namespace App\Policies;

use App\Enums\LetterStatusEnum;
use App\Enums\PermissionEnum;
use App\Enums\RoleEnum;
use App\Models\Letter;
use App\Models\User;

class LetterPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can(PermissionEnum::VIEW_ALL_LETTER);
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewByWilayah(User $user): bool
    {
        return $user->hasRole(RoleEnum::PENGURUS_WILAYAH)
            && $user->can(PermissionEnum::CREATE_LETTER_FOR_RELAWAN);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Letter $letter): bool
    {
        if ($user->can(PermissionEnum::VIEW_ALL_LETTER)) {
            return true;
        }

        if ($user->can([PermissionEnum::VIEW_LETTER, PermissionEnum::VIEW_RELAWAN_LETTER])) {
            return $letter->created_by == $user->id
                || $letter->createdBy?->branch_id == $user->branch_id
                || $letter->recipients()
                ->where('users.id', $user->id)
                ->orWhere('users.branch_id', $user->branch_id)
                ->exists();
        }

        if ($user->can(PermissionEnum::VIEW_LETTER)) {
            // Izinkan hanya jika pengguna adalah pengirim atau penerima ajuan surat
            return $letter->created_by == $user->id
                || $letter->recipients()->where('users.id', $user->id)->exists();;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can(PermissionEnum::CREATE_LETTER);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Letter $letter): bool
    {
        // Izinkan jika pengguna adalah pengirim ajuan surat dan statusnya masih 'MENUNGGU' / 'REVISI'
        if ($user->can(PermissionEnum::EDIT_LETTER)) {
            return $letter->created_by == $user->id
                && in_array($letter->status, [
                    LetterStatusEnum::MENUNGGU,
                    LetterStatusEnum::REVISI
                ]);
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function destroy(User $user, Letter $letter): bool
    {
        if ($user->can(PermissionEnum::DELETE_ALL_LETTERS)) {
            // Izinkan penghapusan 7 hari sejak pembaruan terakhir jika statusnya adalah 'DRAFT' atau 'REVISI'
            if (
                $letter->status == LetterStatusEnum::DITOLAK
                || ($letter->status == LetterStatusEnum::REVISI
                    && $letter->updated_at?->diffInDays() >= 7)
            ) {
                return true;
            }
        }

        if ($user->can(PermissionEnum::DELETE_LETTER)) {
            // Izinkan jika pengguna adalah pengirim ajuan surat dan statusnya masih 'MENUNGGU'
            return $letter->created_by == $user->id
                && $letter->status == LetterStatusEnum::MENUNGGU;
        }

        return false;
    }

    /**
     * Determine whether the user can review letter.
     */
    public function download(User $user, Letter $letter): bool
    {
        if ($user->can(PermissionEnum::HANDLE_LETTER)) {
            return true;
        }

        return $this->view($user, $letter)
            && $letter->status == LetterStatusEnum::SELESAI;
    }

    /**
     * Determine whether the user can review letter.
     */
    public function handleSubmission(User $user, Letter $letter): bool
    {
        return $user->can(PermissionEnum::HANDLE_LETTER)
            && in_array($letter->status, [LetterStatusEnum::DIPROSES]);
    }
}
