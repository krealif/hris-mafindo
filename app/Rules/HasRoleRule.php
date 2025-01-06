<?php

namespace App\Rules;

use Closure;
use App\Models\User;
use App\Enums\RoleEnum;
use Illuminate\Contracts\Validation\ValidationRule;

class HasRoleRule implements ValidationRule
{
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
        /** @var \App\Models\User $user */
        $user = User::with('roles')->find($value);

        if (!$user) {
            $fail("User not found.");
            return;
        }

        if ($user->hasRole($this->roles)) {
            return; // User has at least one of the roles, pass validation
        }

        // If user doesn't have any of the roles, fail validation
        $roleList = implode(', ', $this->roles);
        $fail("The user does not have any of the required roles: {$roleList}.");
    }
}
