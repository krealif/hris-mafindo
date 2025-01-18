<?php

namespace App\Enums;

enum EventStatusEnum: string
{
    case AKTIF = 'aktif';
    case SELESAI = 'selesai';

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
            self::AKTIF => 'badge-outline bg-blue-lt text-blue',
            self::SELESAI => 'badge-outline bg-green-lt text-green',
        };
    }
}
