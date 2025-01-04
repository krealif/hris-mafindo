<?php

namespace App\Observers;

use App\Models\Letter;
use Illuminate\Support\Facades\Storage;

class LetterObserver
{
    public function updated(Letter $letter): void
    {
        $originalAttachment = $letter->getOriginal('attachment');
        if ($originalAttachment && $letter->isDirty('attachment')) {
            Storage::disk('local')->delete($originalAttachment);
        }

        $originalResultFile = $letter->getOriginal('result_file');
        if ($originalResultFile && $letter->isDirty('result_file')) {
            Storage::disk('local')->delete($originalResultFile);
        }
    }

    public function deleted(Letter $letter): void
    {
        if (! is_null($letter->attachment)) {
            Storage::disk('local')->delete($letter->attachment);
        }

        if (! is_null($letter->result_file)) {
            Storage::disk('local')->delete($letter->result_file);
        }
    }
}
