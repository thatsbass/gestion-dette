<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\UserPhotoUploaded;
use App\Services\QrCodeService;
use App\Services\PdfService;
use App\Jobs\EmailJob;

class HandleEmailListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(UserPhotoUploaded $event): void
    {
        $user = $event->user;
        $qrCodePath = app("App\Services\QrCodeService")->generateQrCode(
            $user->login
        );
        $pdf = app("App\Services\PdfService")->generateUserPdf(
            $event->user,
            $event->user->photo,
            $qrCodePath
        );
        EmailJob::dispatch($user, $pdf);
    }
}
