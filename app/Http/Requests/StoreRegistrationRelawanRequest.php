<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;

class StoreRegistrationRelawanRequest extends FormRequest
{
    use UserDataValidationRules;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $noRelawanRule = [
            'nullable',
            'string',
            'max:255',
            'unique:temp_users',
            Rule::unique('users', 'no_relawan')->ignore(Auth::user()),
        ];

        if ($this->boolean('_isDraft')) {
            return [
                ...$this->relawanRules(false),
                'no_relawan' => $noRelawanRule,
            ];
        }

        return [
            ...$this->relawanRules(),
            'no_relawan' => $noRelawanRule,
            'foto' => [
                Auth::user()?->foto ? 'sometimes' : 'required',
                File::image()
                    ->min(1)
                    ->max(1 * 1024)
                    ->dimensions(Rule::dimensions()->minWidth(128)->maxWidth(2000)->ratio(1 / 1)),
            ],
        ];
    }
}
