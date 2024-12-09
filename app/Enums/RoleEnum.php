<?php

namespace App\Enums;

enum RoleEnum: string
{
    case ADMIN = 'admin';
    case PENGURUS = 'pengurus';
    case RELAWAN = 'relawan';
    case RELAWAN_BARU = 'relawan-baru';
}
