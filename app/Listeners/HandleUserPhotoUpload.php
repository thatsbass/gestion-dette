<?php

namespace App\Listeners;

use App\Events\UserPhotoUploaded;
use App\Jobs\PhotoUploadJob;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class HandleUserPhotoUpload implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(UserPhotoUploaded $event)
    {
        PhotoUploadJob::dispatch($event->user->photo, $event->user->id)->delay(now()->addMinutes(10));
    }
}
