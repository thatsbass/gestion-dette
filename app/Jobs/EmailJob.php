<?php

namespace App\Jobs;

use App\Mail\UserCreationMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class EmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;
    protected $pdf;

    public function __construct($user, $pdf)
    {
        $this->user = $user;
        $this->pdf = $pdf;
    }

    public function handle()
{
    try {
        Mail::to($this->user->login)->send(new UserCreationMail($this->user, $this->pdf));
    } catch (\Exception $e) {
        Log::error('Email job failed: ' . $e->getMessage());
    }
}

}
