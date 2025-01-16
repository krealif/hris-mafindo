<?php

namespace App\Enums;

enum LetterStatusEnum: string
{
    case MENUNGGU = 'menunggu';
    case DIPROSES = 'diproses';
    case REVISI = 'revisi';
    case SELESAI = 'selesai';
    case DITOLAK = 'ditolak';

    public function label(): string
    {
        return ucwords($this->value);
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

    public function badge(): string
    {
        return match ($this) {
            self::MENUNGGU => 'badge-outline bg-secondary-lt text-dark',
            self::DIPROSES => 'badge-outline bg-blue-lt text-blue',
            self::REVISI => 'badge-outline bg-orange-lt text-orange',
            self::SELESAI => 'badge-outline bg-green-lt text-green',
            self::DITOLAK => 'badge-outline bg-red-lt text-red',
        };
    }
}
