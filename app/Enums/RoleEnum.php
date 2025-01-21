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
        return ucwords(str_replace('-', ' ', $this->value));
    }

    /**
     * @return array<string, string>
     */
    public static function labels(): array
    {
        return array_combine(
            array_map(fn($case) => $case->value, self::cases()),
            array_map(fn($case) => $case->label(), self::cases())
        );
    }

    /**
     * @return array<string>
     */
    public static function values(): array
    {
        return array_map(fn($role) => $role->value, self::cases());
    }

    public function badge(): string
    {
        return match ($this) {
            self::ADMIN => 'bg-secondary',
            self::PENGURUS_WILAYAH => 'bg-indigo',
            self::RELAWAN_WILAYAH => 'bg-blue',
            self::RELAWAN_BARU => 'bg-azure',
        };
    }
}
