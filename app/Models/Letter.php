<?php

namespace App\Models;

use App\Enums\LetterStatusEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Letter extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'template_id',
        'submitted_by_id',
        'submitted_for_id',
        'content',
        'status',
        'message',
        'file',
        'uploaded_by',
        'uploaded_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array{content: 'object', status: 'App\Enums\LetterStatusEnum'}
     */
    protected function casts(): array
    {
        return [
            'content' => 'object',
            'status' => LetterStatusEnum::class,
        ];
    }

    /**
     * @return BelongsTo<\App\Models\User, $this>
     */
    public function submittedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'submitted_by_id');
    }

    /**
     * @return BelongsTo<\App\Models\User, $this>
     */
    public function submittedFor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'submitted_for_id');
    }

    /**
     * @return BelongsTo<\App\Models\LetterTemplate, $this>
     */
    public function template(): BelongsTo
    {
        return $this->belongsTo(LetterTemplate::class, 'template_id');
    }
}
