<?php

namespace App\Models;

use App\Enums\LetterStatusEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Letter extends Model
{
    protected static function booted()
    {
        static::creating(function ($letterSubmission) {
            $letterSubmission->status = LetterStatusEnum::MENUNGGU->value;
        });
    }
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'letter_template_id',
        'contents',
        'status',
        'file',
        'admin',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'contents' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function letterTemplate(): BelongsTo
    {
        return $this->belongsTo(LetterTemplate::class);
    }
}
