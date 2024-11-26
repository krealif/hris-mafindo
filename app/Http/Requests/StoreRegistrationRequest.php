<?php

namespace App\Http\Requests;

use App\Models\User;
use App\Models\Registration;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use App\Actions\Fortify\PasswordValidationRules;
use App\Enums\RegistrationTypeEnum;

class StoreRegistrationRequest extends FormRequest
{
    use PasswordValidationRules;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $form = $this->route('formType');

        if ($form == RegistrationTypeEnum::RELAWAN_BARU->value) {
            return [
                'agama' => ['required'],
            ];
        } else {
            return [
                'gender' => ['required'],
            ];
        }

        return [];
    }
}
