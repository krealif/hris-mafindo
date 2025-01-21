<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;

class UpdateProfilePengurusRequest extends FormRequest
{
    use UserDataValidationRules;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = Arr::except($this->pengurusRules(), [
            'branch_id'
        ]);

        return $rules;
    }
}
