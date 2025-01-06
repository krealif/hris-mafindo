<?php

namespace App\Filters;

use InvalidArgumentException;
use Illuminate\Support\Facades\Auth;
use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

/**
 * @implements Filter<\App\Models\Letter>
 */
class FilterRelawanWilayahLetter implements Filter
{
    public function __invoke(Builder $query, mixed $value, string $property): void
    {
        $query->where(function ($query) use ($value) {
            $query->where('created_by', $value)
                ->orWhereHas('recipients', function ($query) use ($value) {
                    $query->where('user_id', $value);
                });
        });
    }
}
