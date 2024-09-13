<?php

return [
    'default' => env('MESSAGING_DRIVER', 'twilio'),

    'drivers' => [
        'twilio' => [
            'class' => App\Services\Messaging\TwilioService::class,
            'config' => [
                'sid' => env('TWILIO_ACCOUNT_SID'),
                'auth_token' => env('TWILIO_AUTH_TOKEN'),
                'from' => env('TWILIO_PHONE'),
            ],
        ],

        'infobip' => [
            'class' => App\Services\Messaging\InfobipService::class,
            'config' => [
                'api_key' => env('INFOBIP_API_KEY'),
                'api_url' => env('INFOBIP_API_URL'),
            ],
        ],

    ],
];






/* 

config('messaging.drivers.twilio.config.sid');
config('messaging.drivers.twilio.config.auth_token');
config('messaging.drivers.twilio.config.from');

*/