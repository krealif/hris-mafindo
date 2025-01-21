<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

/**
 * @implements Filter<\App\Models\User>
 */
class FilterRole implements Filter
{
    public function __invoke(Builder $query, mixed $value, string $property): void
    {
        $value = is_array($value) ? $value : [$value];

        $query->whereHas('roles', function ($query) use ($value) {
            $query->whereIn('name', $value);
        });
    }
}
