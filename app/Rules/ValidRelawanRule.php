<?php

namespace App\Rules;

use Closure;
use App\Models\User;
use App\Enums\RoleEnum;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidRelawanRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $user = User::where('id', $value)
            ->role([RoleEnum::RELAWAN_WILAYAH, RoleEnum::RELAWAN_BARU])
            ->first();

        if (!$user) {
            $fail('Pengguna yang dipilih harus memiliki role relawan.');
        }
    }
}
