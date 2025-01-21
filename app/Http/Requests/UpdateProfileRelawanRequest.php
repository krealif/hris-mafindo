<?php

namespace App\Http\Requests;

use App\Enums\PermissionEnum;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\File;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRelawanRequest extends FormRequest
{
    use UserDataValidationRules;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        /** @var \App\Models\User $currentUser */
        $currentUser = Auth::user();

        /** @var \App\Models\User|null $user */
        $user = request()->route('user') ?? $currentUser;

        $noRelawanRule = [
            'nullable',
            'string',
            'max:255',
            'unique:temp_users',
            Rule::unique('users', 'no_relawan')->ignore($user),
        ];

        $rules = [
            ...$this->relawanRules(),
            'no_relawan' => $noRelawanRule,
            'foto' => [
                $user?->foto ? 'sometimes' : 'required',
                File::image()
                    ->min(1)
                    ->max(1 * 1024)
                    ->dimensions(Rule::dimensions()->minWidth(128)->maxWidth(2000)->ratio(1 / 1)),
            ],
        ];

        if ($currentUser->hasPermissionTo(PermissionEnum::EDIT_ALL_USER)) {
            $rules = Arr::except($rules, [
                'foto',
            ]);
        } else {
            $rules = Arr::except($rules, [
                'thn_bergabung',
                'branch_id',
                'no_relawan'
            ]);
        }

        return $rules;
    }
}
