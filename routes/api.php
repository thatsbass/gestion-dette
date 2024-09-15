<?php
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DetteController;
use Illuminate\Support\Facades\Route;
use App\Services\DetteService;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ArchiveDetteController;

Route::get("/test", function (DetteService $detteService) {
    return index($detteService);
});

function index(DetteService $detteService)
{
    $all_dette = $detteService->getTotalDueByClient()->toArray();
    return $all_dette;
}

Route::group(["prefix" => "v1"], function () {
    // Authentification
    Route::post("login", [AuthController::class, "login"]);
    Route::post("register", [
        ClientController::class,
        "createUserForClient",
    ])->middleware("auth:api", "checkRole:Admin,Boutiquier");
    Route::post("logout", [AuthController::class, "logout"])->middleware(
        "auth:api"
    );
    Route::post("refresh", [AuthController::class, "refresh"]);

    // Route pour les clients
    Route::middleware(["auth:api", "checkRole:Admin,Boutiquier"])->group(
        function () {
            Route::prefix("clients")->group(function () {
                Route::post("/", [ClientController::class, "store"]);
                Route::put("/{id}", [ClientController::class, "update"]);
                Route::delete("/{id}", [ClientController::class, "destroy"]);
                Route::get("/clients", [ClientController::class, "index"]);
            });

            // Rechercher un client par numéro de téléphone
            Route::post("/telephone", [
                ClientController::class,
                "findByTelephone",
            ]);
            Route::patch("{id}", [ClientController::class, "update"]);
        }
    );
    // Route pour les users
    Route::prefix("users")->group(function () {
        Route::post("/", [UserController::class, "store"]);
        Route::put("/{id}", [UserController::class, "update"]);
        Route::delete("/{id}", [UserController::class, "destroy"]);
        Route::get("/", [UserController::class, "index"]);
    });

    Route::prefix("notification")->group(function () {
        // Route pour notifier un client spécifique
        Route::get("client/{id}", [
            NotificationController::class,
            "notifySingleClient",
        ]);

        // Route pour notifier les clients sélectionnés
        Route::post("client/all", [
            NotificationController::class,
            "notifyForClientsSelected",
        ]);

        // Route pour envoyer un message personnalisé à des clients sélectionnés
        Route::post("client/message", [
            NotificationController::class,
            "sendCustomMessageForClientsSelected",
        ]);
    });
    // Archivage
    Route::get("api/v1/dettes/archive", [
        ArchiveDetteController::class,
        "index",
    ]);
    Route::get("api/v1/archive/clients/{clientId}/dettes", [
        ArchiveDetteController::class,
        "getByClient",
    ]);
    Route::get("api/v1/archive/dettes/{id}", [
        ArchiveDetteController::class,
        "getById",
    ]);
    Route::get("api/v1/restaure/{date}", [
        ArchiveDetteController::class,
        "restoreByDate",
    ]);
    Route::get("api/v1/restaure/dette/{id}", [
        ArchiveDetteController::class,
        "restoreById",
    ]);
    Route::get("api/v1/restaure/client/{clientId}", [
        ArchiveDetteController::class,
        "restoreByClient",
    ]);

    // Route pour les articles
    Route::middleware(["auth:api", "checkRole:Boutiquier,Admin"])->group(
        function () {
            Route::prefix("articles")->group(function () {
                Route::post("/", [ArticleController::class, "store"]);
                Route::get("/", [ArticleController::class, "index"]);
                Route::get("{id}", [ArticleController::class, "show"]);
                Route::put("{id}", [ArticleController::class, "update"]);
                Route::delete("{id}", [ArticleController::class, "destroy"]);
                Route::patch("{id}/stock", [
                    ArticleController::class,
                    "updateStock",
                ]);
                Route::post("/stock", [
                    ArticleController::class,
                    "updateMultipleStock",
                ]);
                Route::get("{id}", [ArticleController::class, "showById"]);
                Route::post("libelle", [
                    ArticleController::class,
                    "showByLibelle",
                ]);
            });
        }
    );

    Route::middleware(["auth:api", "checkRole:Admin,Boutiquier"])->group(
        function () {
            Route::prefix("dettes")->group(function () {
                Route::post("/", [DetteController::class, "store"]);
                Route::get("/", [DetteController::class, "index"]);
                Route::get("/{id}", [DetteController::class, "show"]);
                Route::post("/{id}/paiements", [
                    DetteController::class,
                    "addPayment",
                ]);
                Route::get("/{id}/articles", [
                    DetteController::class,
                    "getArticles",
                ]);
                Route::get("/{id}/paiements", [
                    DetteController::class,
                    "getPayments",
                ]);
            });
        }
    );

    Route::any("{segment}", function () {
        return response()->json([
            "error" => "Invalid URL.",
        ]);
    })->where("segment", ".*");
});

// Route pour accès non autorisé
Route::get("unauthorized", function () {
    return response()->json(
        [
            "error" => "Unauthorized.",
        ],
        401
    );
})->name("unauthorized");
