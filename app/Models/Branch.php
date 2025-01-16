<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Branch extends Model
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'staff',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array{staff: 'object'}
     */
    protected function casts(): array
    {
        return [
            'staff' => 'object',
        ];
    }

    /**
     * @return HasMany<\App\Models\User, $this>
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
