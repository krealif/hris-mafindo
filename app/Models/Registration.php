<?php

namespace App\Models;

use App\Enums\RegistrationBaruStepEnum;
use App\Enums\RegistrationLamaStepEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Registration extends Model
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'type',
        'status',
        'step',
        'message',
        'data',
        'updated_at',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * @return BelongsTo<\App\Models\User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
