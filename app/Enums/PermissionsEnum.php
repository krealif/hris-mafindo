<?php

namespace App\Enums;

enum PermissionsEnum: string
{
    case VIEW_REGISTRATION = 'view-registration';
    case ACCEPT_REGISTRATION = 'accept-registration';
    case REJECT_REGISTRATION = 'reject-registration';
}
