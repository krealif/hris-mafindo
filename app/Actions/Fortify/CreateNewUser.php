<?php

namespace App\Actions\Fortify;

use App\Models\User;
use App\Models\TempUser;
use App\Models\Registration;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'nama' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class),
            ],
            'password' => $this->passwordRules(),
        ])->validate();

        $user = User::create([
            'nama' => $input['nama'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
        ]);

        $tempUser = TempUser::where('nama', $user->nama)
            ->where('email', $user->email)
            ->first();

        if ($tempUser) {
            DB::transaction(function () use ($user, $tempUser) {
                $user->update([
                    'no_relawan' => $tempUser->no_relawan,
                    'branch_id' => $tempUser->branch_id,
                ]);

                $userDetail = $tempUser->userDetail;
                $userDetail?->update([
                    'user_id' => $user->id
                ]);

                Registration::create([
                    'user_id' => $user->id,
                    'type' => \App\Enums\RegistrationTypeEnum::RELAWAN_WILAYAH,
                    'status' => \App\Enums\RegistrationStatusEnum::DRAFT,
                    'step' => \App\Enums\RegistrationLamaStepEnum::MENGISI,
                ]);

                $tempUser->delete();
            });
        }

        return $user;
    }
}
