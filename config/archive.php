<?php
return [
    "default" => env("ARCHIVING_DRIVER"),

    "drivers" => [
        "mongodb" => [
            "class" => App\Services\Archive\MongoDBArchiveService::class,
            "config" => [
                "dsn" => env("MONGO_DB_URI"),
                "database" => env("MONGO_DB_DATABASE"),
            ],
        ],

        "firebase" => [
            "class" => App\Services\Archive\FirebaseArchiveService::class,
            "config" => [
                "credentials" => env("FIREBASE_CREDENTIALS"),
                "database_url" => env("FIREBASE_DATABASE_URL"),
            ],
        ],
    ],
];
