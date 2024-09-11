<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use App\Models\User;

class UserPhotoUploaded
{
    use SerializesModels, Dispatchable ;

    public $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    
    }
}
