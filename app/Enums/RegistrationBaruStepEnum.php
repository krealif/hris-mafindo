<?php

namespace App\Enums;

enum RegistrationBaruStepEnum: string
{
    case MENGISI = 'mengisi';
    case PROFILING = 'profiling';
    case WAWANCARA = 'wawancara';
    case TERHUBUNG = 'terhubung';
    case PELATIHAN = 'pelatihan';

    public function index(): int|false
    {
        return array_search($this, self::cases());
    }

    public function label(): string
    {
        return ucwords(str_replace('-', ' ', $this->value));
    }

    public function badge(): string
    {
        return match ($this) {
            self::MENGISI => 'bg-gray text-muted',
            self::PROFILING => 'bg-cyan',
            self::WAWANCARA => 'bg-purple',
            self::TERHUBUNG => 'bg-lime',
            self::PELATIHAN => 'bg-green',
        };
    }

    /**
     * @return array<string, string>
     */
    public static function labels(): array
    {
        return [
            self::MENGISI->value => 'Mengisi Form',
            self::PROFILING->value => 'Profiling Medsos',
            self::WAWANCARA->value => 'Wawancara',
            self::TERHUBUNG->value => 'Terhubung Wilayah',
            self::PELATIHAN->value => 'Pelatihan Dasar Relawan',
        ];
    }
}
