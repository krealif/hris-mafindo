<?php

namespace App\Models;

use App\Enums\LetterStatusEnum;
use App\Observers\LetterObserver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[ObservedBy([LetterObserver::class])]
class Letter extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'created_by',
        'title',
        'body',
        'attachment',
        'type',
        'status',
        'message',
        'result_file',
        'uploaded_by',
        'uploaded_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array{status: 'App\Enums\LetterStatusEnum'}
     */
    protected function casts(): array
    {
        return [
            'status' => LetterStatusEnum::class,
        ];
    }

    /**
     * @return BelongsTo<\App\Models\User, $this>
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * @return BelongsToMany<\App\Models\User, $this>
     */
    public function recipients(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'letter_recipients');
    }
}
