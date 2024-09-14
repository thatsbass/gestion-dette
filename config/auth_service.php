<?php

return [
    "driver" => env("AUTH_DRIVER", "sanctum"),

    "drivers" => [
        "sanctum" => [
            "class" => \App\Services\Authentification\SanctumAuthService::class,
            "config" => [
                "expiration" => env("SANCTUM_EXPIRATION", 60 * 24),
            ],
        ],
        "passport" => [
            "class" =>
                \App\Services\Authentification\PassportAuthService::class,
            "config" => [
                "client_id" => env("PASSPORT_PASSWORD_GRANT_CLIENT_ID"),
                "client_secret" => env("PASSPORT_PASSWORD_GRANT_CLIENT_SECRET"),
                "expiration" => env("PASSPORT_EXPIRATION", 60 * 24),
            ],
        ],
    ],
];
