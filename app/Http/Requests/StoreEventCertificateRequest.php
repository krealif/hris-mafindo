<?php

namespace App\Http\Requests;

use App\Enums\RoleEnum;
use App\Rules\HasRoleRule;
use Illuminate\Validation\Rules\File;
use Illuminate\Foundation\Http\FormRequest;

class StoreEventCertificateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'relawan' => [
                $this->isMethod('POST') ? 'required' : 'nullable',
                new HasRoleRule([
                    RoleEnum::RELAWAN_WILAYAH,
                    RoleEnum::RELAWAN_BARU,
                ])
            ],
            'file' => [
                'required',
                File::types(['pdf'])
                    ->min('1kb')
                    ->max('2mb'),
            ]
        ];
    }
}
