<?php

namespace App\Enums;

enum RegistrationStatusEnum: string
{
    case DRAFT = 'draft';
    case DIPROSES = 'diproses';
    case REVISI = 'revisi';
    case SELESAI = 'selesai';
    case DITOLAK = 'ditolak';

    public function label(): string
    {
        return match ($this) {
            self::DRAFT => 'Draft',
            self::DIPROSES => 'Diproses',
            self::REVISI => 'Revisi',
            self::SELESAI => 'Selesai',
            self::DITOLAK => 'Ditolak',
        };
    }

    public function badge(): string
    {
        return match ($this) {
            self::DRAFT => 'badge-outline text-dark',
            self::DIPROSES => 'badge-outline text-blue',
            self::REVISI => 'badge-outline text-orange',
            self::SELESAI => 'badge-outline text-green',
            self::DITOLAK => 'badge-outline text-red',
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
