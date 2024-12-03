<?php

namespace App\Enums;

enum RegistrationStepEnum: string
{
    case MENGISI = 'mengisi';
    case PROFILING = 'profiling';
    case WAWANCARA = 'wawancara';
    case TERHUBUNG = 'terhubung';
    case PELATIHAN = 'pelatihan';

    public function step(): int
    {
        return array_search($this, self::cases()) + 1;
    }

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
