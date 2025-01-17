<?php

namespace App\Models;

use App\Observers\EventCertificateObserver;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[ObservedBy([EventCertificateObserver::class])]
class EventCertificate extends Pivot
{
    protected $table = 'event_certificates';

    /**
     * @return HasMany<\App\Models\User, $this>
     */
    public function user()
    {
        return $this->belongsTo(User::class)
            ->select('nama');
    }
}
