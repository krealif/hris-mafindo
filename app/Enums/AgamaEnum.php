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
    case LAIN_LAIN = 'lain-lain';
    case TIDAK_MENYEBUTKAN = 'tidak-menyebutkan';

    public function label(): string
    {
        return match ($this) {
            self::ISLAM => 'Islam',
            self::PROTESTAN => 'Protestan',
            self::KATOLIK => 'Katolik',
            self::HINDU => 'Hindu',
            self::BUDDHA => 'Buddha',
            self::KONGHUCU => 'Konghucu',
            self::LAIN_LAIN => 'Lain-lain',
            self::TIDAK_MENYEBUTKAN => 'Tidak Menyebutkan',
        };
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
}
