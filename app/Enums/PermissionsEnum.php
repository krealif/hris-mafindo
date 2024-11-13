<?php

namespace App\Enums;

enum PermissionsEnum: string
{
    case LIST_REGISTRATION = 'list-registration';
    case ACCEPT_REGISTRATION = 'accept-registration';
    case REJECT_REGISTRATION = 'reject-registration';
}
