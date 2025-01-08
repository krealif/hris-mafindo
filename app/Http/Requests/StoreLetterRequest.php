<?php

namespace App\Http\Requests;

use App\Enums\PermissionEnum;
use App\Enums\RoleEnum;
use App\Rules\HasRoleRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\File;

class StoreLetterRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $recipientsRequired = ($user->hasRole('admin')
            || $this->boolean('_withRecipient')
            || $this->input('recipients'))
            && $user->canAny(
                [
                    PermissionEnum::CREATE_LETTER_FOR_RELAWAN,
                    PermissionEnum::CREATE_LETTER_FOR_PENGURUS,
                ]
            );

        return [
            'title' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string', 'max:5000'],
            'attachment' => [
                'nullable',
                File::types(['pdf', 'doc', 'docx', 'png', 'jpg', 'jpeg'])
                    ->min('1kb')
                    ->max('2mb'),
            ],
            'recipients' => [$recipientsRequired ? 'required' : 'nullable', 'array', 'max:10'],
            'recipients.*' => [
                $user->hasRole(RoleEnum::ADMIN)
                    ? new HasRoleRule([
                        RoleEnum::PENGURUS_WILAYAH,
                        RoleEnum::RELAWAN_WILAYAH,
                        RoleEnum::RELAWAN_BARU,
                    ])
                    : new HasRoleRule([
                        RoleEnum::RELAWAN_WILAYAH,
                        RoleEnum::RELAWAN_BARU,
                    ]),
            ],
        ];
    }
}
