<?php

namespace App\Models;

use App\Enums\RegistrationBaruStepEnum;
use App\Enums\RegistrationLamaStepEnum;
use App\Enums\RegistrationStatusEnum;
use App\Enums\RegistrationTypeEnum;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Registration extends Model
{
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
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array{
     *   type: 'App\Enums\RegistrationTypeEnum',
     *   status: 'App\Enums\RegistrationStatusEnum',
     * }
     */
    protected function casts(): array
    {
        return [
            'type' => RegistrationTypeEnum::class,
            'status' => RegistrationStatusEnum::class,
        ];
    }

    /**
     * Get the first role name of the user.
     * If the user has no roles, returns "No Role".
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute<RegistrationBaruStepEnum|RegistrationLamaStepEnum, never>
     */
    protected function step(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => $this->type == RegistrationTypeEnum::RELAWAN_BARU
                ? RegistrationBaruStepEnum::from($value)
                : RegistrationLamaStepEnum::from($value),
        );
    }

    /**
     * @return BelongsTo<\App\Models\User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
