<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Material;
use App\Enums\PermissionEnum;

class MaterialPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can(PermissionEnum::VIEW_MATERIAL);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Material $material): bool
    {
        return $user->can(PermissionEnum::VIEW_MATERIAL);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can(PermissionEnum::CREATE_MATERIAL);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Material $material): bool
    {
        return $user->can(PermissionEnum::EDIT_MATERIAL);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Material $material): bool
    {
        return $user->can(PermissionEnum::DELETE_MATERIAL);
    }
}
