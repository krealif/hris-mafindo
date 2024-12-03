<?php

namespace App\Enums;

enum GenderEnum: string
{
    case LAKI_LAKI = 'laki-laki';
    case PEREMPUAN = 'perempuan';
    case TIDAK_MENYEBUTKAN = 'tidak-menyebutkan';

    public static function labels(): array
    {
        return [
            self::LAKI_LAKI->value => 'Laki-laki',
            self::PEREMPUAN->value => 'Perempuan',
            self::TIDAK_MENYEBUTKAN->value => 'Tidak Menyebutkan',
        ];
    }
}
