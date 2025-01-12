<?php

namespace App\Enums;

enum PermissionEnum: string
{
    /**
     * Surat Permission
     */
    case VIEW_LETTER = 'view-letter';
    case CREATE_LETTER = 'create-letter';
    case EDIT_LETTER = 'edit-letter';
    case DELETE_LETTER = 'delete-letter';

    case VIEW_RELAWAN_LETTER = 'view-relawan-letter';
    case CREATE_LETTER_FOR_RELAWAN = 'create-letter-for-relawan';
    case CREATE_LETTER_FOR_PENGURUS = 'create-letter-for-pengurus';

    case VIEW_ALL_LETTER = 'view-all-letter';
    case HANDLE_LETTER = 'handle-letter';
    case DELETE_ALL_LETTER = 'delete-all-letter';
}
