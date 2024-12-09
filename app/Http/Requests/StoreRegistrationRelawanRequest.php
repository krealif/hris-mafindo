<?php

namespace App\Http\Requests;

use App\Enums\AgamaEnum;
use App\Enums\GenderEnum;
use Illuminate\Validation\Rule;
use App\Enums\RegistrationTypeEnum;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\Rules\File;
use Illuminate\Foundation\Http\FormRequest;
use App\Actions\Fortify\PasswordValidationRules;

class StoreRegistrationRelawanRequest extends FormRequest
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
            'panggilan' => ['string', 'max:255'],
            'tgl_lahir' => ['date'],
            'gender' => [new Enum(GenderEnum::class)],
            'agama' => ['nullable', new Enum(AgamaEnum::class)],
            'alamat' => ['string', 'max:255'],
            'disabilitas' => ['nullable', 'string', 'max:255'],
            'foto' => [
                'nullable',
                File::image()
                    ->min(1)
                    ->max(3 * 1024)
                    ->dimensions(Rule::dimensions()->minWidth(128)->maxWidth(3840)->ratio(1 / 1)),
            ],
            'no_wa' => ['numeric', 'digits_between:10,15'],
            'no_hp' => ['nullable', 'numeric', 'digits_between:10,15'],
            'alamat' => ['string', 'max:255'],
            'bidang_keahlian' => ['nullable', 'string', 'max:255'],
            'bidang_mafindo' => ['string'],
            'tahun_bergabung' => ['numeric', 'min:2010'],
            'branch_id' => ['exists:branches,id'],
            'pdr' => ['numeric'],
            'medsos' => ['nullable', 'array'],
            'medsos.*' => ['nullable', 'string', 'max:255'],
            'pendidikan' => ['nullable', 'array'],
            'pendidikan.*.tingkat' => ['nullable', 'string', 'max:255'],
            'pendidikan.*.institusi' => ['nullable', 'string', 'max:255'],
            'pendidikan.*.jurusan' => ['nullable', 'string', 'max:255'],
            'pekerjaan' => ['nullable', 'array'],
            'pekerjaan.*.jabatan' => ['nullable', 'string', 'max:255'],
            'pekerjaan.*.lembaga' => ['nullable', 'string', 'max:255'],
            'pekerjaan.*.tahun' => ['nullable', 'string', 'regex:/^\d{4}-\d{4}$/'],
            'sertifikat' => ['nullable', 'array'],
            'sertifikat.*.nama' => ['nullable', 'string', 'max:255'],
            'sertifikat.*.masa' => ['nullable', 'string', 'regex:/^\d{4}-\d{4}$/'],
        ];

        if ($this->input('mode') === 'draft') {
            return [
                ...$rules,
                '*' => ['nullable'],
            ];
        }

        return match ($this->route('type')) {
            RegistrationTypeEnum::RELAWAN_BARU->value => [
                '*' => ['required'],
                ...$rules,
            ],
            RegistrationTypeEnum::RELAWAN_WILAYAH->value => [
                '*' => ['required'],
                ...$rules,
            ],
            default => [],
        };
    }
}
