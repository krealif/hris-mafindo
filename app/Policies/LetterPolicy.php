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
        if ($user->can(PermissionEnum::VIEW_LETTER)) {
            // Izinkan hanya jika pengguna adalah pengaju atau penerima surat
            $canView = $user->id == $letter->submitted_by_id
                || $user->id == $letter->submitted_for_id;

            // Izinkan jika surat tersebut milik relawan dengan cabang yang sama
            if ($user->can(PermissionEnum::VIEW_RELAWAN_LETTER)) {
                return $canView
                    || $user->branch_id == $letter->submittedBy?->branch_id
                    || $user->branch_id == $letter->submittedFor?->branch_id;
            }

            return $canView;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
        // return $user->can(PermissionEnum::CREATE_LETTER);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Letter $letter): bool
    {
        // Izinkan jika pengguna adalah pengaju surat dan statusnya masih 'MENUNGGU'
        if ($user->can(PermissionEnum::EDIT_LETTER)) {
            return $user->id == $letter->submitted_by_id
                && $letter->status == LetterStatusEnum::MENUNGGU;
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
            // Izinkan jika pengguna adalah pengaju surat dan statusnya masih 'MENUNGGU'
            return $user->id == $letter->submitted_by_id
                && $letter->status == LetterStatusEnum::MENUNGGU;
        }

        return false;
    }

    /**
     * Determine whether the user can review letter.
     */
    public function review(User $user, Letter $letter): bool
    {
        return $user->can(PermissionEnum::REVIEW_ALL_LETTER)
            || in_array($letter->status, [LetterStatusEnum::DIPROSES, LetterStatusEnum::REVISI]);
    }

    /**
     * Determine whether the user can review letter.
     */
    public function download(User $user, Letter $letter): bool
    {
        if ($user->can(PermissionEnum::REVIEW_ALL_LETTER)) {
            return true;
        }

        return $letter->status == LetterStatusEnum::SELESAI;
    }
}
