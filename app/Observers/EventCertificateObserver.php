<?php

namespace App\Observers;

use App\Models\EventCertificate;
use Illuminate\Support\Facades\Storage;

class EventCertificateObserver
{
    public function updated(EventCertificate $certificate): void
    {
        $originalFile = $certificate->getOriginal('file');
        if ($originalFile && $certificate->isDirty('file')) {
            Storage::disk('local')->delete($originalFile);
        }
    }
}
