<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateMigrationRelawanRequest extends FormRequest
{
    use UserDataValidationRules;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            ...$this->relawanRules(false),
            'email' => [
                'required',
                'string',
                'max:255',
                Rule::email()
                    ->rfcCompliant(strict: true)
                    ->preventSpoofing(),
                Rule::unique('temp_users')->ignore($this->tempUser),
                Rule::unique('users'),
            ],
            'no_relawan' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('temp_users')->ignore($this->tempUser),
                Rule::unique('users'),
            ],
        ];
    }
}
