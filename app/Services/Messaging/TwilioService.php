<?php
namespace App\Services\Messaging;

use Twilio\Rest\Client;

class TwilioService implements MessagingServiceInterface
{
    protected $client;
    protected $from;

    public function __construct($sid, $authToken, $from)
    {
        $this->client = new Client($sid, $authToken);
        $this->from = $from;
    }

    public function sendMessage(string $phoneNumber, string $message)
    {
        $this->client->messages->create($phoneNumber, [
            'from' => $this->from,
            'body' => $message
        ]);
    }
}
