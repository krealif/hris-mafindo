<?php

namespace App\Enums;

enum RegistrationTypeEnum: string
{
    case RELAWAN_BARU = 'relawan-baru';
    case RELAWAN_WILAYAH = 'relawan-wilayah';
    case PENGURUS_WILAYAH = 'pengurus-wilayah';

    public function label(): string
    {
        return match ($this) {
            self::RELAWAN_BARU => 'Relawan Baru',
            self::RELAWAN_WILAYAH => 'Relawan Wilayah',
            self::PENGURUS_WILAYAH => 'Pengurus Wilayah',
        };
    }

    public function badge(): string
    {
        return match ($this) {
            self::RELAWAN_BARU => 'bg-blue',
            self::RELAWAN_WILAYAH => 'bg-indigo',
            self::PENGURUS_WILAYAH => 'bg-pink',
        };
    }
}
