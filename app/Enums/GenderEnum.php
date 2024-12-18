<?php

namespace App\Enums;

enum GenderEnum: string
{
    case LAKI_LAKI = 'laki-laki';
    case PEREMPUAN = 'perempuan';
    case TIDAK_MENYEBUTKAN = 'tidak-menyebutkan';

    public function label(): string
    {
        return match ($this) {
            self::LAKI_LAKI => 'Laki-laki',
            self::PEREMPUAN => 'Perempuan',
            self::TIDAK_MENYEBUTKAN => 'Tidak Menyebutkan'
        };
    }

    /**
     * @return array<string, string>
     */
    public static function labels(): array
    {
        return array_combine(
            array_map(fn ($case) => $case->value, self::cases()),
            array_map(fn ($case) => $case->label(), self::cases())
        );
    }
}
