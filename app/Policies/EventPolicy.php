<?php

namespace App\Policies;

use App\Enums\EventStatusEnum;
use App\Models\User;
use App\Models\Event;
use App\Enums\PermissionEnum;
use Illuminate\Auth\Access\Response;

class EventPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can(PermissionEnum::VIEW_EVENT);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Event $event): bool
    {
        return $user->can(PermissionEnum::VIEW_EVENT);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can(PermissionEnum::CREATE_EVENT);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Event $event): bool
    {
        return $user->can(PermissionEnum::EDIT_EVENT);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Event $event): bool
    {
        // TODO: Need Update
        return $user->can(PermissionEnum::DELETE_EVENT)
            && $event->status == EventStatusEnum::AKTIF;
    }

    public function finish(User $user, Event $event): bool
    {
        return $user->can(PermissionEnum::EDIT_EVENT)
            && $event->status == EventStatusEnum::AKTIF
            && $event->has_started;
    }

    public function join(User $user, Event $event): bool
    {
        return $user->can(PermissionEnum::JOIN_EVENT)
            && $event->status == EventStatusEnum::AKTIF;
    }

    public function manageCertificate(User $user, Event $event): bool
    {
        return $user->can(PermissionEnum::MANAGE_CERTIFICATE)
            && $event->status == EventStatusEnum::SELESAI;
    }
}
