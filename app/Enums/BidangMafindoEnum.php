<?php

namespace App\Enums;

enum BidangMafindoEnum: string
{
    case LITERASI_DIGITAL = 'literasi_digital';
    case MEDIA_SOSIAL = 'media_sosial';
    case TATA_KELOLA = 'tata_kelola';
    case PENELITIAN = 'penelitian';
    case MENULIS = 'menulis';
    case DESAIN_GRAFIS = 'desain_grafis';
    case HUMAS_KERJASAMA = 'humas_kerjasama';

    public function label(): string
    {
        return match ($this) {
            self::LITERASI_DIGITAL => 'Edukasi Literasi Digital kepada Masyarakat/Public Speaking',
            self::MEDIA_SOSIAL => 'Pengelolaan Media Sosial',
            self::TATA_KELOLA => 'Tata Kelola Organisasi dan Pengembagan SDM',
            self::PENELITIAN => 'Penelitian',
            self::MENULIS => 'Menulis',
            self::DESAIN_GRAFIS => 'Desain dan Grafis',
            self::HUMAS_KERJASAMA => 'Hubungan Masyarakat dan Kerjasama',
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
