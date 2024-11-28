<?php

namespace App\Enums;

enum RoleEnum: string
{
    case ADMIN = 'admin';
    case PENGURUS = 'pengurus';
    case RELAWAN = 'relawan';

    public function label(): string
    {
        return match ($this) {
            static::ADMIN => 'Admin',
            static::PENGURUS => 'Pengurus',
            static::RELAWAN => 'Relawan',
        };
    }
}
