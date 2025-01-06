<?php

namespace App\Filters;

use InvalidArgumentException;
use Illuminate\Support\Facades\Auth;
use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

/**
 * @implements Filter<\App\Models\Letter>
 */
class FilterRecipientLetter implements Filter
{
    public function __invoke(Builder $query, mixed $value, string $property): void
    {
        $query->whereHas('recipients', function ($query) use ($value) {
            $query->where('user_id', $value);
        });
    }
}
