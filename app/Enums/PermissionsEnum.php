<?php

namespace App\Enums;

enum PermissionsEnum: string
{
    case VIEW_ALL_LETTER = 'view-all-letter';
    case VIEW_LETTER = 'view-letter';
    case CREATE_LETTER = 'create-letter';
    case EDIT_LETTER = 'edit-letter';
    case DELETE_LETTER = 'delete-letter';
    case REVIEW_LETTER = 'review-letter';
}
