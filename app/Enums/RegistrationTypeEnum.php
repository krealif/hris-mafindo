<?php

namespace App\Enums;

enum RegistrationTypeEnum: string
{
    case RELAWAN_BARU = 'relawan-baru';
    case RELAWAN_WILAYAH = 'relawan-wilayah';
    case PENGURUS_WILAYAH = 'pengurus-wilayah';

    public static function value(): array
    {
        return array_map(fn($case) => $case->value, self::cases());
    }
}
