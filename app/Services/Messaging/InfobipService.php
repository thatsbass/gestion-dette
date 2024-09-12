<?php

namespace App\Services\Messaging;

use GuzzleHttp\Client;

class InfobipService implements MessagingServiceInterface
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function sendMessage( $to, $message)
    {
        $this->client->post('https://infobip-api-url', [
            'json' => [
                'to' => $to,
                'text' => $message
            ],
            'headers' => [
                'Authorization' => 'App ' . env('INFOBIP_API_KEY')
            ]
        ]);
    }
}
