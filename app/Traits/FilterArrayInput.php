<?php

namespace App\Traits;

trait FilterArrayInput
{
    /**
     * Handle array input fields by filtering empty values.
     *
     * @param  array<string, mixed>  $validated
     * @param  array<string>  $fields
     * @return array<string, mixed>
     */
    private function filterArrayInput(array $validated, array $fields): array
    {
        foreach ($fields as $field) {
            if (isset($validated[$field])) {
                $filteredValue = $this->filterFieldValue($validated[$field]);

                if ($filteredValue !== null) {
                    $validated[$field] = [...$filteredValue];
                } else {
                    unset($validated[$field]);
                }
            } else {
                $validated[$field] = null;
            }
        }

        return $validated;
    }

    /**
     * Filter individual field value recursively.
     *
     * @param mixed $value
     * @return mixed|null
     */
    private function filterFieldValue($value): mixed
    {
        if (is_array($value)) {
            // Recursively filter nested arrays
            $filteredArray = array_filter($value, function ($item) {
                return $this->filterFieldValue($item) !== null;
            });
            return !empty($filteredArray) ? $filteredArray : null;
        }

        return !is_null($value) && $value !== '' ? $value : null;
    }
}
