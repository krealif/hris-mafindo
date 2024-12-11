<?php

namespace App\Enums;

enum AgamaEnum: string
{
    case ISLAM = 'islam';
    case PROTESTAN = 'protestan';
    case KATOLIK = 'katolik';
    case HINDU = 'hindu';
    case BUDDHA = 'buddha';
    case KONGHUCU = 'konghucu';

    public function label(): string
    {
        return match ($this) {
            self::ISLAM => 'Islam',
            self::PROTESTAN => 'Protestan',
            self::KATOLIK => 'Katolik',
            self::HINDU => 'Hindu',
            self::BUDDHA => 'Buddha',
            self::KONGHUCU => 'Konghucu',
        };
    }

    public static function labels(): array
    {
        return array_combine(
            array_map(fn($case) => $case->value, self::cases()),
            array_map(fn($case) => $case->label(), self::cases())
        );
    }
}
