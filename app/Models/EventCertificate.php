<?php

namespace App\Models;

use App\Observers\EventCertificateObserver;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[ObservedBy([EventCertificateObserver::class])]
class EventCertificate extends Pivot
{
    protected $table = 'event_certificates';

    /**
     * @return BelongsTo<\App\Models\User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)
            ->select('nama');
    }
}
