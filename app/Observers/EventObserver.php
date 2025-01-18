<?php

namespace App\Observers;

use App\Models\Event;
use Illuminate\Support\Facades\Storage;

class EventObserver
{
    public function updated(Event $event): void
    {
        $originalCover = $event->getOriginal('cover');
        if ($originalCover && $event->isDirty('cover')) {
            Storage::disk('public')->delete($originalCover);
        }
    }

    public function deleted(Event $event): void
    {
        if ($event->cover) {
            Storage::disk('public')->delete($event->cover);
        }
    }
}
