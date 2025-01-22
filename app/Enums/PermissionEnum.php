<?php

namespace App\Enums;

enum PermissionEnum: string
{
    case VIEW_ALL_USER = 'view-all-user';
    case VIEW_RELAWAN_USER = 'view-relawan-user';
    case EDIT_ALL_USER = 'edit-all-user';

    case VIEW_LETTER = 'view-letter';
    case CREATE_LETTER = 'create-letter';
    case EDIT_LETTER = 'edit-letter';
    case DELETE_LETTER = 'delete-letter';

    case VIEW_RELAWAN_LETTER = 'view-relawan-letter';
    case CREATE_LETTER_FOR_RELAWAN = 'create-letter-for-relawan';

    case VIEW_ALL_LETTER = 'view-all-letter';
    case CREATE_LETTER_FOR_ALL = 'create-letter-for-all';
    case HANDLE_LETTER = 'handle-letter';
    case DELETE_ALL_LETTER = 'delete-all-letter';

    case VIEW_EVENT = 'view-event';
    case CREATE_EVENT = 'create-event';
    case EDIT_EVENT = 'edit-event';
    case DELETE_EVENT = 'delete-event';
    case JOIN_EVENT = 'join-event';

    case VIEW_PARTICIPANT = 'view-participant';

    case VIEW_ALL_CERTIFICATE = 'view-all-certificate';
    case VIEW_CERTIFICATE = 'view-certificate';
    case MANAGE_CERTIFICATE = 'manage-certificate';

    case VIEW_MATERIAL = 'view-material';
    case CREATE_MATERIAL = 'create-material';
    case EDIT_MATERIAL = 'edit-material';
    case DELETE_MATERIAL = 'delete-material';
    case JOIN_MATERIAL = 'join-material';
}
