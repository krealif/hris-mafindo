<?php

namespace App\Enums;

enum RegistrationTypeEnum: string
{
    case RELAWAN_BARU = 'relawan-baru';
    case RELAWAN_WILAYAH = 'relawan-wilayah';
    case PENGURUS_WILAYAH = 'pengurus-wilayah';
}
