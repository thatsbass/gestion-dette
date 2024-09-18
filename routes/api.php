<?php
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DetteController;
use App\Http\Controllers\DemandeController;
use Illuminate\Support\Facades\Route;
use App\Services\DetteService;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ArchiveDetteController;

// Route de test
Route::get("/test", function (DetteService $detteService) {
    return index($detteService);
});

function index(DetteService $detteService)
{
    return $detteService->getTotalDueByClient()->toArray();
}


Route::get('/api-docs', function () {
    return response()->file(storage_path('api-docs/swagger.yaml'));
});

Route::get('/docs', function () {
    return view('swagger-ui');
});


// Routes version 1
Route::group(["prefix" => "v1"], function () {
    // Authentification
    Route::post("login", [AuthController::class, "login"]);
    Route::post("register", [
        ClientController::class,
        "createUserForClient",
    ]);
    Route::post("logout", [AuthController::class, "logout"])->middleware(
        "auth:api"
    );
    Route::post("refresh", [AuthController::class, "refresh"]);

    // Routes pour les clients
    Route::middleware(["auth:api", "checkRole:Admin,Boutiquier"])->group(
        function () {
            Route::prefix("clients")->group(function () {
                Route::post("/", [ClientController::class, "store"]);
                Route::get("/", [ClientController::class, "index"]);
                Route::put("/{id}", [ClientController::class, "update"]);
                Route::delete("/{id}", [ClientController::class, "destroy"]);
                Route::post("/telephone", [
                    ClientController::class,
                    "findByTelephone",
                ]);
            });
            Route::patch("{id}", [ClientController::class, "update"]);
        }
    );

    // Routes pour les utilisateurs
    Route::post("/users", [UserController::class, "store"]);
    Route::middleware(["auth:api", "checkRole:Admin, Boutiquier"])->group(function () {
        Route::prefix("users")->group(function () {
            // Route::post("/", [UserController::class, "store"]);
            Route::get("/", [UserController::class, "index"]);
            Route::put("/{id}", [UserController::class, "update"]);
            Route::delete("/{id}", [UserController::class, "destroy"]);
        });
    });

    // Routes pour les notifications
    Route::middleware(["auth:api", "checkRole:Admin,Boutiquier"])->group(
        function () {
            Route::prefix("notification")->group(function () {
                Route::get("client/{id}", [
                    NotificationController::class,
                    "notifySingleClient",
                ]);
                Route::post("client/all", [
                    NotificationController::class,
                    "notifyForClientsSelected",
                ]);
                Route::post("client/message", [
                    NotificationController::class,
                    "sendCustomMessageForClientsSelected",
                ]);
            });
        }
    );

    // Routes pour l'archivage
    Route::middleware(["auth:api", "checkRole:Admin,Boutiquier"])->group(
        function () {
            Route::prefix("archive")->group(function () {
                Route::get("dettes", [ArchiveDetteController::class, "index"]);
                Route::get("clients/{clientId}/dettes", [
                    ArchiveDetteController::class,
                    "getByClient",
                ]);
                Route::get("dettes/{id}", [
                    ArchiveDetteController::class,
                    "getById",
                ]);
                Route::get("restaure/{date}", [
                    ArchiveDetteController::class,
                    "restoreByDate",
                ]);
                Route::get("restaure/dette/{id}", [
                    ArchiveDetteController::class,
                    "restoreById",
                ]);
                Route::get("restaure/client/{clientId}", [
                    ArchiveDetteController::class,
                    "restoreByClient",
                ]);
            });
        }
    );

    // Routes pour les articles
    Route::middleware(["auth:api", "checkRole:Boutiquier,Admin,Client"])->group(
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
                Route::post("libelle", [
                    ArticleController::class,
                    "showByLibelle",
                ]);
            });
        }
    );

    // Routes pour les dettes
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

    // Routes pour les demandes

    Route::middleware(["auth:api", "checkRole:Admin,Boutiquier,Client"])->group(
        function () {
            Route::prefix("demandes")->group(function () {
                Route::post("/", [DemandeController::class, "store"]);
                Route::get("/", [DemandeController::class, "index"]);
                Route::post("/{id}/relance", [
                    DemandeController::class,
                    "relance",
                ]);
                Route::get("/notifications/client", [
                    DemandeController::class,
                    "notificationsClient",
                ]);
            });
        }
    );
    Route::middleware(["auth:api", "checkRole:Boutiquier"])->group(
        function () {
            Route::get('demandes/notifications', [DemandeController::class, 'getNotifications']);
            Route::get('demandes/all', [DemandeController::class, 'getAllDemandes']);
        });

        Route::middleware(["auth:api", "checkRole:Boutiquier"])->group(
            function () {
        Route::get('demandes/{id}/disponible', [DemandeController::class, 'checkDisponibilite']);
        // Valider ou annuler une demande
        Route::patch('demandes/{id}', [DemandeController::class, 'validerOuAnnuler']);

            });
  
    // Route pour les URL non valides
    Route::any("{segment}", function () {
        return response()->json(["error" => "Invalid URL."], 404);
    })->where("segment", ".*");
});

// Route pour accès non autorisé
Route::get("unauthorized", function () {
    return response()->json(["error" => "Unauthorized."], 401);
})->name("unauthorized");
