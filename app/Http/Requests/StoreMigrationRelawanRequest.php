<?php

namespace App\Http\Requests;

use App\Models\User;
use App\Models\TempUser;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\File;
use Illuminate\Foundation\Http\FormRequest;

class StoreMigrationRelawanRequest extends FormRequest
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
                'email',
                'max:255',
                Rule::unique('temp_users'),
                Rule::unique('users'),
            ],
            'no_relawan' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('users'),
                Rule::unique('temp_users')
            ],
        ];
    }
}
