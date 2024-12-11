<?php

namespace App\Enums;

enum TingkatPendidikanEnum: string
{
    case SMA_SMK = 'SMA/SMK';
    case D3 = 'D3';
    case D4 = 'D4';
    case S1 = 'S1';
    case S2 = 'S2';
    case S3 = 'S3';

    public static function labels(): array
    {
        return array_combine(
            array_map(fn($case) => $case->value, self::cases()),
            array_map(fn($case) => $case->value, self::cases())
        );
    }
}
