<?php

namespace App\Enums;

enum DisabilitasEnum: string
{
    case AUTISME = 'autisme';
    case TULI_BUTA = 'tuli-buta';
    case KETULIAN = 'ketulian';
    case GANGGUAN_EMOSI = 'gangguan-emosi';
    case GANGGUAN_PENDENGARAN = 'gangguan-pendengaran';
    case DISABILITAS_INTELEKTUAL = 'disabilitas-intelektual';
    case DISABILITAS_GANDA = 'disabilitas-ganda';
    case ORTOPEDI = 'ortopedi';
    case CACAT_PERSEPSI = 'cacat-persepsi';
    case CEDERA_OTAK = 'cedera-otak';
    case DISFUNGSI_OTAK_MINIMAL = 'disfungsi-otak-minimal';
    case DISLEKSIA = 'disleksia';
    case AFASIA_PERKEMBANGAN = 'afasia-perkembangan';

    public function label(): string
    {
        return ucwords(str_replace('-', ' ', $this->value));
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
}
