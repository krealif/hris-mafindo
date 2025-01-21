<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

/**
 * @implements Filter<\App\Models\Letter>
 */
class FilterRole implements Filter
{
    public function __invoke(Builder $query, mixed $value, string $property): void
    {
        $query->role($value);
    }
}
