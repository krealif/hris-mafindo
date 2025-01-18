<?php

namespace App\Enums;

enum EventTypeEnum: string
{
    case TERBUKA = 'terbuka';
    case TERBATAS = 'terbatas';

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
            self::TERBUKA => 'bg-blue',
            self::TERBATAS => 'bg-pink',
        };
    }
}
