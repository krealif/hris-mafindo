<?php

namespace App\Policies;

use App\Enums\LetterStatusEnum;
use App\Enums\PermissionsEnum;
use App\Models\Letter;
use App\Models\User;

class LetterPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return ($user->can(PermissionsEnum::VIEW_ALL_LETTER));
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Letter $letter): bool
    {
        if ($user->can(PermissionsEnum::VIEW_ALL_LETTER)) {
            return true;
        }

        if ($user->can(PermissionsEnum::VIEW_LETTER)) {
            return $user->id == $letter->user_id;
        }

        return false;
    }

    /**
     * Determine whether the user can review letter.
     */
    public function review(User $user, Letter $letter): bool
    {
        if ($user->can(PermissionsEnum::REVIEW_LETTER)) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can(PermissionsEnum::CREATE_LETTER);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Letter $letter): bool
    {
        if ($letter->status != LetterStatusEnum::MENUNGGU->value) {
            return false;
        }

        if ($user->can(PermissionsEnum::EDIT_LETTER)) {
            return $user->id == $letter->user_id;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Letter $letter): bool
    {
        if (!$letter->status == LetterStatusEnum::MENUNGGU->value) {
            return false;
        }

        if ($user->can(PermissionsEnum::DELETE_LETTER)) {
            return $user->id == $letter->user_id;
        }

        return false;
    }
}
