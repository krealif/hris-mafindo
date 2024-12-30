<?php

namespace App\Enums;

enum RegistrationLamaStepEnum: string
{
    case MENGISI = 'mengisi';
    case VERIFIKASI = 'verifikasi';

    public function index(): int|false
    {
        return array_search($this, self::cases());
    }

    public function label(): string
    {
        return ucwords($this->value);
    }

    public function badge(): string
    {
        return match ($this) {
            self::MENGISI => 'bg-dark',
            self::VERIFIKASI => 'bg-green',
        };
    }

    /**
     * @return array<string, string>
     */
    public static function steps(): array
    {
        return [
            self::MENGISI->value => 'Mengisi Form',
            self::VERIFIKASI->value => 'Verifikasi oleh Admin',
        ];
    }
}
