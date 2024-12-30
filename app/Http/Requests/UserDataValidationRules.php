<?php

namespace App\Http\Requests;

use App\Enums\AgamaEnum;
use App\Enums\GenderEnum;
use App\Enums\RoleEnum;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;

trait UserDataValidationRules
{
    /**
     * Get the validation rules used to validate relawan data.
     *
     * @return array<string, mixed>
     */
    protected function relawanRules(bool $isRequired = true): array
    {
        return [
            'nama' => ['required', 'string', 'max:255'],
            'panggilan' => [$isRequired ? 'required' : 'nullable', 'string', 'max:255'],
            'tgl_lahir' => [$isRequired ? 'required' : 'nullable', 'date'],
            'gender' => [$isRequired ? 'required' : 'nullable', Rule::Enum(GenderEnum::class)],
            'agama' => [$isRequired ? 'required' : 'nullable', Rule::Enum(AgamaEnum::class)],
            'alamat' => [$isRequired ? 'required' : 'nullable', 'string', 'max:255'],
            'disabilitas' => ['nullable', 'string', 'max:255'],

            'foto' => [
                'sometimes',
                File::image()
                    ->min(1)
                    ->max(1 * 1024)
                    ->dimensions(Rule::dimensions()->minWidth(128)->maxWidth(2000)->ratio(1 / 1)),
            ],

            'no_wa' => [$isRequired ? 'required' : 'nullable', 'numeric', 'digits_between:10,15'],
            'no_hp' => ['nullable', 'numeric', 'digits_between:10,15'],
            'medsos' => ['nullable', 'array'],
            'medsos.*' => ['nullable', 'string', 'max:255'],

            'thn_bergabung' => [$isRequired ? 'required' : 'nullable', 'numeric', 'min:2010'],
            'branch_id' => [$isRequired ? 'required' : 'nullable', 'exists:branches,id'],
            'pdr' => [$isRequired ? 'required' : 'nullable', 'numeric'],
            'no_relawan' => ['nullable', 'string', 'max:255'],

            'bidang_keahlian' => ['nullable', 'string', 'max:255'],
            'bidang_mafindo' => [$isRequired ? 'required' : 'nullable', 'string'],

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
    }

    /**
     * Get the validation rules used to validate user data.
     *
     * @return array<string, mixed>
     */
    protected function pengurusRules(bool $isRequired = true): array
    {
        return [
            'nama' => ['required', 'string', 'max:255'],
            'branch_id' => [
                'required',
                'exists:branches,id',
                Rule::unique('users')->where(function ($query) {
                    return $query->whereExists(function ($query) {
                        $query->select(DB::raw(1))
                            ->from('registrations')
                            ->whereRaw('registrations.user_id = users.id')
                            ->where('type', RoleEnum::PENGURUS_WILAYAH);
                    });
                })->ignore($this->user()),
            ],
            'pengurus' => ['nullable', 'array'],
            'pengurus.*' => ['nullable', 'string', 'max:255'],
        ];
    }
}
