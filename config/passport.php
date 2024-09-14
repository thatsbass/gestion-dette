<?php

return [
    "guard" => "web",

    "private_key" => env("PASSPORT_PRIVATE_KEY"),

    "public_key" => env("PASSPORT_PUBLIC_KEY"),

    "connection" => env("PASSPORT_CONNECTION"),

    "client_uuids" => false,

    "personal_access_token" => [
        "enabled" => env("PASSPORT_PERSONAL_ACCESS_TOKEN_ENABLED", true),
    ],

    "tokens" => [
        "expiration" => env("PASSPORT_EXPIRATION", 60 * 24),
    ],
    "personal_access_client" => [
        "id" => env("PASSPORT_PERSONAL_ACCESS_CLIENT_ID"),
        "secret" => env("PASSPORT_PERSONAL_ACCESS_CLIENT_SECRET"),
    ],
];
