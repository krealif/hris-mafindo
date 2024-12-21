<?php

namespace App\Enums;

enum RoleEnum: string
{
    case ADMIN = 'admin';
    case PENGURUS_WILAYAH = 'pengurus-wilayah';
    case RELAWAN_WILAYAH = 'relawan-wilayah';
    case RELAWAN_BARU = 'relawan-baru';

    public function label(): string
    {
        return match ($this) {
            self::ADMIN => 'Admin',
            self::PENGURUS_WILAYAH => 'Pengurus Wilayah',
            self::RELAWAN_WILAYAH => 'Relawan',
            self::RELAWAN_BARU => 'Relawan Baru',
        };
    }

    /**
     * @return array<string>
     */
    public static function values(): array
    {
        return array_map(fn($role) => $role->value, self::cases());
    }
}
