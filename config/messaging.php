<?php

return [
    'default' => env('MESSAGING_DRIVER', 'twilio'),

    'drivers' => [
        'twilio' => [
            'class' => App\Services\Messaging\TwilioService::class,
            'config' => [
                'sid' => env('TWILIO_SID'),
                'auth_token' => env('TWILIO_AUTH_TOKEN'),
                'from' => env('TWILIO_FROM'),
            ],
        ],

        'infobip' => [
            'class' => App\Services\Messaging\InfobipService::class,
            'config' => [
                'api_key' => env('INFOBIP_API_KEY'),
                'api_url' => 'https://infobip-api-url',
            ],
        ],
        
    ],
];
