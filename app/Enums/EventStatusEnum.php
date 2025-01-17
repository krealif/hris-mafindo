<?php

namespace App\Enums;

enum EventStatusEnum: string
{
    case AKTIF = 'aktif';
    case SELESAI = 'selesai';

    public function label(): string
    {
        return match ($this) {
            self::AKTIF => 'Aktif',
            self::SELESAI => 'Selesai',
        };
    }

    public function badge(): string
    {
        return match ($this) {
            self::AKTIF => 'badge-outline bg-blue-lt text-blue',
            self::SELESAI => 'badge-outline bg-green-lt text-green',
        };
    }
}
