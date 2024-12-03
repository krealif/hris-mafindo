<?php

namespace App\Enums;

enum RegistrationStatusEnum: string
{
    case DRAFT = 'draft';
    case DIPROSES = 'diproses';
    case REVISI = 'revisi';
    case SELESAI = 'selesai';
}
