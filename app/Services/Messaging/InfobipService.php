<?php
namespace App\Services\Messaging;

// use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;
use Log;

class InfobipService implements MessagingServiceInterface
{
    protected $apiKey;
    protected $apiUrl;

    public function __construct($apiKey, $apiUrl)
    {
        $this->apiKey = $apiKey;
        $this->apiUrl = $apiUrl;
    }

    public function sendMessage(string $phoneNumber, string $message)
    {
        $response = Http::withHeaders([
            'Authorization' => "App {$this->apiKey}",
            'Content-Type' => 'application/json',
        ])->post("{$this->apiUrl}/sms/2/text/single", [
            'from' => 'Jawen',
            'to' => $phoneNumber,
            'text' => $message,
        ]);

        return $response->successful();
    }


}
