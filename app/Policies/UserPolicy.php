<?php

namespace App\Policies;

use App\Models\User;
use App\Enums\PermissionEnum;
use Illuminate\Http\RedirectResponse;

class UserPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $currentUser): bool
    {
        return $currentUser->can(PermissionEnum::VIEW_ALL_USER);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $currentUser, User $user): bool|RedirectResponse
    {
        if ($user->is_approved) {
            if ($currentUser->can(PermissionEnum::VIEW_ALL_USER)) {
                return true;
            }

            if ($currentUser->can(PermissionEnum::VIEW_RELAWAN_USER)) {
                return $currentUser->branch_id == $user->branch_id;
            }

            if ($user->is($currentUser)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $currentUser): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $currentUser, User $user): bool
    {
        if ($user->is_approved) {
            if ($currentUser->can(PermissionEnum::EDIT_ALL_USER)) {
                return !$user->is($currentUser);
            }

            if ($user->is($currentUser)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $currentUser, User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $currentUser, User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $currentUser, User $user): bool
    {
        return false;
    }
}
