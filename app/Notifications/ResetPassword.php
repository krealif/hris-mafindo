<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword as CoreResetPassword;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class ResetPassword extends CoreResetPassword implements ShouldQueue
{
    use Queueable;
}
