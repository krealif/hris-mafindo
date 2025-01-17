<?php

namespace App\Models;

use App\Enums\EventTypeEnum;
use App\Enums\EventStatusEnum;
use App\Observers\EventObserver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int|null $has_joined
 */
#[ObservedBy([EventObserver::class])]
class Event extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'description',
        'start_date',
        'cover',
        'type',
        'quota',
        'status',
        'meeting_url',
        'recording_url',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array{
     *     start_date: 'datetime',
     *     type: 'App\Enums\EventTypeEnum',
     *     status: 'App\Enums\EventStatusEnum',
     * }
     */
    protected function casts(): array
    {
        return [
            'start_date' => 'datetime',
            'type' => EventTypeEnum::class,
            'status' => EventStatusEnum::class,
        ];
    }

    /**
     * Determine if the event has started.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute<bool, never>
     */
    protected function hasStarted(): Attribute
    {
        return Attribute::make(
            get: fn() => now()->greaterThanOrEqualTo($this->start_date),
        );
    }

    /**
     * @return BelongsToMany<\App\Models\User, $this>
     */
    public function participants(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'event_participants')
            ->withPivot('created_at')
            ->withTimestamps();
    }

    /**
     * @return BelongsToMany<\App\Models\User, $this>
     */
    public function certificates(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'event_certificates')
            ->using(EventCertificate::class)
            ->withPivot('id', 'created_at', 'file')
            ->withTimestamps();
    }
}
