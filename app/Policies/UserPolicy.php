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
    public function viewAny(User $authUser): bool
    {
        return $authUser->can(PermissionEnum::VIEW_ALL_USER);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $authUser, User $user): bool|RedirectResponse
    {
        if ($authUser->can(PermissionEnum::VIEW_ALL_USER)) {
            return true;
        }

        if ($authUser->can(PermissionEnum::VIEW_RELAWAN_USER)) {
            return $authUser->branch_id == $user->branch_id;
        }

        if ($user->is($authUser)) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $authUser): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $authUser, User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $authUser, User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $authUser, User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $authUser, User $user): bool
    {
        return false;
    }
}
