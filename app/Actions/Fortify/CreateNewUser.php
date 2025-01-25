<?php

namespace App\Actions\Fortify;

use App\Models\Registration;
use App\Models\TempUser;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
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
        $validated = $this->validateInput($input);

        $user = User::create([
            'nama' => $validated['nama'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $tempUser = TempUser::where('email', $user->email)->first();

        if ($tempUser) {
            $this->integrateOldData($user, $tempUser);
        } else {
            Registration::create([
                'user_id' => $user->id,
                'status' => \App\Enums\RegistrationStatusEnum::DRAFT,
                'step' => \App\Enums\RegistrationLamaStepEnum::MENGISI,
            ]);
        }

        return $user;
    }

    /**
     * Handle TempUser data and associate it with the newly created user.
     */
    private function integrateOldData(User $user, TempUser $tempUser): void
    {
        DB::transaction(function () use ($user, $tempUser) {
            $user->update([
                'no_relawan' => $tempUser->no_relawan,
                'branch_id' => $tempUser->branch_id,
            ]);

            $userDetail = $tempUser->detail;
            if ($userDetail) {
                $userDetail->update(['user_id' => $user->id]);
            }

            Registration::create([
                'user_id' => $user->id,
                'type' => \App\Enums\RegistrationTypeEnum::RELAWAN_WILAYAH,
                'status' => \App\Enums\RegistrationStatusEnum::DRAFT,
                'step' => \App\Enums\RegistrationLamaStepEnum::MENGISI,
            ]);

            $tempUser->delete();
        });
    }

    /**
     * Validate the input data for creating a new user.
     *
     * @param  array<string, mixed>  $input  The input data to be validated.
     * @return array<string, mixed> The validated input data.
     */
    private function validateInput(array $input): array
    {
        return Validator::make($input, [
            'nama' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'max:255',
                Rule::email()
                    ->rfcCompliant(strict: true)
                    ->preventSpoofing(),
                Rule::unique(User::class),
            ],
            'password' => $this->passwordRules(),
        ])->validate();
    }
}
