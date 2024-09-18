<?php

namespace App\Observers;

use App\Models\User;
use App\Events\UserPhotoUploaded;

class UserObserver
{
    public function created(User $user)
    {
        UserPhotoUploaded::dispatch($user);
    }
}
