<?php

namespace App\Policies;

use App\Enums\LetterStatusEnum;
use App\Enums\PermissionEnum;
use App\Models\Letter;
use App\Models\User;

class LetterPolicy
{
    /**
     * Determine whether the user can view any letters.
     */
    public function viewAny(User $user): bool
    {
        return $user->can(PermissionEnum::VIEW_ALL_LETTER);
    }

    /**
     * Determine whether the user can view letters by their wilayah (branch).
     */
    public function viewByWilayah(User $user): bool
    {
        return $user->can(PermissionEnum::VIEW_RELAWAN_LETTER);
    }

    /**
     * Determine whether the user can view the letter.
     */
    public function view(User $user, Letter $letter): bool
    {
        if ($user->can(PermissionEnum::VIEW_ALL_LETTER)) {
            return true;
        }

        // Izinkan akses untuk role PENGURUS:
        // 1. Jika pengguna adalah pengirim atau tujuan permohonan surat.
        // 2. Jika permohonan surat tersebut milik relawan dengan wilayah yang.
        if ($user->can([PermissionEnum::VIEW_LETTER, PermissionEnum::VIEW_RELAWAN_LETTER])) {
            return $letter->created_by == $user->id
                || $letter->createdBy?->branch_id == $user->branch_id
                || $letter->recipients()
                ->where(function ($query) use ($user) {
                    $query->where('users.id', $user->id)
                        ->orWhere('users.branch_id', $user->branch_id);
                })
                ->exists();
        }

        if ($user->can(PermissionEnum::VIEW_LETTER)) {
            // Izinkan hanya jika pengguna adalah pengirim atau tujuan permohonan surat
            return $letter->created_by == $user->id
                || $letter->recipients()->where('users.id', $user->id)->exists();
        }

        return false;
    }

    /**
     * Determine whether the user can create a letter.
     */
    public function create(User $user): bool
    {
        return $user->can(PermissionEnum::CREATE_LETTER);
    }

    /**
     * Determine whether the user can update the letter.
     */
    public function update(User $user, Letter $letter): bool
    {
        // Izinkan jika pengguna adalah pengirim permohonan surat dan statusnya masih 'MENUNGGU' / 'REVISI'
        if ($user->can(PermissionEnum::EDIT_LETTER)) {
            return $letter->created_by == $user->id
                && in_array($letter->status, [
                    LetterStatusEnum::MENUNGGU,
                    LetterStatusEnum::REVISI,
                ]);
        }

        return false;
    }

    /**
     * Determine whether the user can delete the letter.
     */
    public function delete(User $user, Letter $letter): bool
    {
        if ($user->can(PermissionEnum::DELETE_ALL_LETTER)) {
            // Izinkan penghapusan 7 hari sejak pembaruan terakhir jika statusnya adalah 'DRAFT' atau 'REVISI'
            if (
                $letter->status == LetterStatusEnum::DITOLAK
                || ($letter->status == LetterStatusEnum::REVISI
                    && $letter->updated_at?->diffInDays() >= 7)
            ) {
                return true;
            }

            // Izinkan penghapusan permohonan surat dengan status 'SELESAI' yang sudah lebih dari 1 tahun
            if (
                $letter->status === LetterStatusEnum::SELESAI &&
                $letter->updated_at?->diffInYears() >= 1
            ) {
                return true;
            }
        }

        if ($user->can(PermissionEnum::DELETE_LETTER)) {
            // Izinkan jika pengguna adalah pengirim permohonan surat dan statusnya masih 'MENUNGGU' / 'REVISI'
            return $letter->created_by == $user->id
                && in_array($letter->status, [
                    LetterStatusEnum::MENUNGGU,
                    LetterStatusEnum::REVISI,
                ]);
        }

        return false;
    }

    /**
     * Determine whether the user can download result file or submission attachment.
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
     * Determine whether the user can handle letter submission.
     */
    public function handleSubmission(User $user, Letter $letter): bool
    {
        return $user->can(PermissionEnum::HANDLE_LETTER)
            && in_array($letter->status, [LetterStatusEnum::DIPROSES]);
    }
}
