<?php

namespace App\Http\Requests;

use App\Actions\Fortify\PasswordValidationRules;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class StoreRegistrationPengurusRequest extends FormRequest
{
    use PasswordValidationRules;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'nama' => ['string', 'max:255'],
            'branch_id' => [
                'exists:branches,id',
                Rule::unique('users')->where(function ($query) {
                    return $query->whereExists(function ($query) {
                        $query->select(DB::raw(1))
                            ->from('registrations')
                            ->whereRaw('registrations.user_id = users.id')
                            ->where('type', 'pengurus-wilayah');
                    });
                })->ignore($this->user()),
            ],
            'pengurus' => ['nullable', 'array'],
            'pengurus.*' => ['nullable', 'string', 'max:255'],
        ];

        if ($this->input('_mode') == 'draft') {
            return [
                ...$rules,
                '*' => ['nullable'],
            ];
        }

        return [
            '*' => ['required'],
            ...$rules,
        ];
    }
}
