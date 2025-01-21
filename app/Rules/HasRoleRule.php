<?php

namespace App\Rules;

use App\Enums\RoleEnum;
use App\Models\User;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class HasRoleRule implements ValidationRule
{
    /**
     * @var string[]
     */
    protected array $roles;

    public function __construct(mixed $roles)
    {
        $this->roles = is_array($roles)
            ? array_map(fn($role) => $role instanceof RoleEnum ? $role->value : (string) $role, $roles)
            : [$roles instanceof RoleEnum ? $roles->value : (string) $roles];
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        /** @var \App\Models\User|null $user */
        $userExistsWithRole = User::where('id', $value)
            ->whereHas('roles', function ($query) {
                $query->whereIn('name', $this->roles);
            })
            ->exists();
        dd($userExistsWithRole);
        if (! $userExistsWithRole) {
            // If user doesn't have any of the roles, fail validation
            $roleList = implode(', ', $this->roles);
            $fail("The user does not have any of the required roles: {$roleList}.");
        }
    }
}
