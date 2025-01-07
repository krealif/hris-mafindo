<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use InvalidArgumentException;
use Spatie\QueryBuilder\Filters\Filter;

/**
 * @implements Filter<\Illuminate\Database\Eloquent\Model>
 */
class FilterDate implements Filter
{
    public function __invoke(Builder $query, mixed $value, string $property): void
    {
        // Extract operator and value, e.g., "<2025-01-04"
        preg_match('/^(<=|>=|<|>|=)?\s*(\S.*)$/', $value, $matches);

        $operator = empty($matches[1]) ? '=' : $matches[1]; // Default to '=' if no operator is specified
        $date = $matches[2] ?? null; // Use null if no date value is found

        // Validate the operator and value
        if (! in_array($operator, ['', '<=', '>=', '<', '>', '='])) {
            throw new InvalidArgumentException("Invalid operator '{$operator}' for filtering.");
        }

        // Ensure the date value exists
        if (empty($date)) {
            throw new InvalidArgumentException('Invalid date value for filtering.');
        }

        // Validate the date format (simple check, can be expanded based on your needs)
        if (! strtotime($date)) {
            throw new InvalidArgumentException("Invalid date value '{$date}' for filtering.");
        }

        // Apply the DATE function with the dynamic operator
        $query->whereRaw("DATE(`$property`) $operator ?", [$date]);
    }
}
