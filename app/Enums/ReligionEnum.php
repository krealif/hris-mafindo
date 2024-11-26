<?php

namespace App\Enums;

enum ReligionEnum: string
{
    case ISLAM = 'islam';
    case PROTESTAN = 'protestan';
    case KATOLIK = 'katolik';
    case HINDU = 'hindu';
    case BUDDHA = 'buddha';
    case KONGHUCU = 'konghucu';

    public static function labels(): array
    {
        return [
            self::ISLAM->value => 'Islam',
            self::PROTESTAN->value => 'Protestan',
            self::KATOLIK->value => 'Katolik',
            self::HINDU->value => 'Hindu',
            self::BUDDHA->value => 'Buddha',
            self::KONGHUCU->value => 'Konghucu',
        ];
    }
}
