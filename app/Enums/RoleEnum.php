<?php

namespace App\Enums;

enum RoleEnum: string
{
    case ADMIN = 'admin';
    case PENGURUS = 'pengurus';
    case RELAWAN = 'relawan';
    case RELAWAN_BARU = 'relawan-baru';

    public static function values(): array
    {
        return array_map(fn($role) => $role->value, self::cases());
    }
}
