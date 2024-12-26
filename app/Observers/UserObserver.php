<?php

namespace App\Observers;

use App\Models\User;
use Illuminate\Support\Facades\Storage;

class UserObserver
{
    public function updated(User $user): void
    {
        $originalPhoto = $user->getOriginal('foto');
        if ($originalPhoto && $user->isDirty('foto')) {
            Storage::disk('public')->delete($originalPhoto);
        }
    }

    public function deleting(User $user): void
    {
        $user->syncRoles([]);
    }

    public function deleted(User $user): void
    {
        if (! is_null($user->foto)) {
            Storage::disk('public')->delete($user->foto);
        }
    }
}
