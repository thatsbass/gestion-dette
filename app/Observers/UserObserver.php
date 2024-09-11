<?php

namespace App\Observers;

use App\Models\User;
use App\Events\UserPhotoUploaded;

class UserObserver
{
    public function created(User $user)
    {
        // if ($user->photo) {
        //     event(new UserPhotoUploaded($user));
        // }
        UserPhotoUploaded::dispatch($user);
    }
}
