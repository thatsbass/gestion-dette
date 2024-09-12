<?php

namespace App\Services\Sms;

use Vonage\Client;
use Vonage\Client\Credentials\Basic;

class VonageSmsService
{
    protected $client;

    public function __construct(array $config)
    {
        $basic = new Basic($config['api_key'], $config['api_secret']);
        $this->client = new Client($basic);
    }

    public function sendSms(string $to, string $message)
    {
        $response = $this->client->message()->send([
            'to' => $to,
            'from' => 'YourApp',
            'text' => $message,
        ]);

        return $response;
    }
}
