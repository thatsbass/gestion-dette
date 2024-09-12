<?php

namespace App\Services\Messaging;

use Twilio\Rest\Client as TwilioClient;
use Log;

class TwilioService implements MessagingServiceInterface
{
    protected $client;

    public function __construct()
    {
        $this->client = new TwilioClient(env("TWILIO_ACCOUNT_SID"), env("TWILIO_AUTH_TOKEN"));
    }

    public function sendMessage( $to, $message)
{
    try {
        $messaging = $message;
        $toClient = $to;
        Log::info("Sending SMS to " . $toClient . " with message " . $messaging); 
        $this->client->messages->create(
            $to,
            [
                'from' => env('TWILIO_PHONE'), 
                'body' => $message,
            ]
        );
    } catch (\Exception $e) {
        Log::error($e->getMessage());
        throw $e;
    }
}

}

