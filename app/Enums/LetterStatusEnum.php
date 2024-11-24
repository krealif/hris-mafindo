<?php

namespace App\Enums;

enum LetterStatusEnum: string
{
    case MENUNGGU = 'menunggu';
    case DIPROSES = 'diproses';
    case SELESAI = 'selesai';
    case DITOLAK = 'ditolak';

    public function label(): string
    {
        return match ($this) {
            static::MENUNGGU => 'menunggu',
            static::DIPROSES => 'diproses',
            static::SELESAI => 'selesai',
            static::DITOLAK => 'ditolak',
        };
    }
}
