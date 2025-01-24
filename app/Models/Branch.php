<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
     * Get the staff information of the branch.
     * This is done because when inputting data, the array is filtered.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute<object, never>
     */
    protected function staff(): Attribute
    {
        return Attribute::make(
            get: function (mixed $value) {
                $staff = json_decode($value, true) ?: [];
                $list = ['sekretaris1', 'sekretaris2', 'bendahara1', 'bendahara2'];

                $staff = array_merge(array_fill_keys($list, null), $staff);

                return (object) $staff;
            },
        );
    }

    /**
     * @return HasMany<\App\Models\User, $this>
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
