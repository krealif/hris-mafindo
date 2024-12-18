<?php

namespace App\Traits;

trait HandlesArrayInput
{
    /**
     * Handle array input fields by filtering empty values.
     *
     * @param  array<string, mixed>  $validated
     * @param  array<string>  $fields
     * @return array<string, mixed>
     */
    private function handleArrayField(array $validated, array $fields): array
    {
        foreach ($fields as $field) {
            // If the field exists, filter empty values
            if (isset($validated[$field])) {
                $validated[$field] = array_filter($validated[$field], function ($item) {
                    return ! empty(array_filter($item, fn ($value) => ! is_null($value) && $value !== ''));
                });
            } else {
                // If the field does not exist, set it to null
                $validated[$field] = null;
            }
        }

        return $validated;
    }
}
