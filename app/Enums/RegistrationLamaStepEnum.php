<?php

namespace App\Enums;

enum RegistrationLamaStepEnum: string
{
    case MENGISI = 'mengisi';
    case VERIFIKASI = 'verifikasi';

    public function step(): int
    {
        return array_search($this, self::cases()) + 1;
    }

    public static function labels(): array
    {
        return [
            self::MENGISI->value => 'Mengisi Form',
            self::VERIFIKASI->value => 'Verifikasi oleh Admin',
        ];
    }
}
