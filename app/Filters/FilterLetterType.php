<?php

namespace App\Filters;

use Illuminate\Support\Facades\Auth;
use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

/**
 * @implements Filter<\App\Models\Letter>
 */
class FilterLetterType implements Filter
{
    public function __invoke(Builder $query, mixed $value, string $property): void
    {
        // If the value is 0, filter out records where 'created_by' is the current user.
        if ($value == 0) {
            $query->where('created_by', '!=', Auth::id());
        }
        // If the value is 1, filter records where 'created_by' is the current user.
        elseif ($value == 1) {
            $query->where('created_by', Auth::id());
        }
    }
}
