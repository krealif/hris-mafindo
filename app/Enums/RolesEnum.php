<?php

namespace App\Enums;

enum RolesEnum: string
{
    // case NAMEINAPP = 'name-in-database';

    case ADMIN = 'admin';
    case PENGURUS = 'pengurus';
    case RELAWAN = 'relawan';

    public function labels(): string
    {
        return match ($this) {
            static::ADMIN => 'Admin',
            static::PENGURUS => 'Pengurus',
            static::RELAWAN => 'Relawan',
        };
    }
}
