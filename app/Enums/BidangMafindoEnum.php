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

    public static function labels(): array
    {
        return [
            self::LITERASI_DIGITAL->value => 'Edukasi Literasi Digital kepada Masyarakat/Public Speaking',
            self::MEDIA_SOSIAL->value => 'Pengelolaan Media Sosial',
            self::TATA_KELOLA->value => 'Tata Kelola Organisasi dan Pengembagan SDM',
            self::PENELITIAN->value => 'Penelitian',
            self::MENULIS->value => 'Menulis',
            self::DESAIN_GRAFIS->value => 'Desain dan Grafis',
            self::HUMAS_KERJASAMA->value => 'Hubungan Masyarakat dan Kerjasama',
        ];
    }
}
