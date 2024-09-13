<?php
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DetteController;
use Illuminate\Support\Facades\Route;
use App\Services\DetteService;


use App\Services\MongoDBService;

Route::get('/test', function (DetteService $detteService) {
    return index($detteService);
});

function index(DetteService $detteService) {
    $all_dette = $detteService->getTotalDueByClient()->toArray();
    return $all_dette;
}


Route::group(['prefix' => 'v1'], function () { 
    // Authentification
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [ClientController::class, 'createUserForClient']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    // Route pour les clients
    Route::prefix('clients')->group(function () {
    Route::post('/', [ClientController::class, 'store']);
    Route::put('/{id}', [ClientController::class, 'update']);
    Route::delete('/{id}', [ClientController::class, 'destroy']);
    Route::get('/clients', [ClientController::class, 'index']);
    // Rechercher un client par numéro de téléphone
    Route::post('/telephone', [ClientController::class, 'findByTelephone']);
    Route::patch('{id}', [ClientController::class, 'update']);
    });
    // Route pour les users
    Route::prefix('users')->group(function () {
        Route::post('/', [UserController::class, 'store']);
        Route::put('/{id}', [UserController::class, 'update']);
        Route::delete('/{id}', [UserController::class, 'destroy']);
        Route::get('/', [UserController::class, 'index']);
    });


    // Route pour les articles
 
Route::prefix('articles')->group(function () {
    Route::post('/', [ArticleController::class, 'store']);
    Route::get('/', [ArticleController::class, 'index']);
    Route::get('{id}', [ArticleController::class, 'show']);
    Route::put('{id}', [ArticleController::class, 'update']);
    Route::delete('{id}', [ArticleController::class, 'destroy']);
    Route::patch('{id}/stock', [ArticleController::class, 'updateStock']);
    Route::post('/stock', [ArticleController::class, 'updateMultipleStock']);
    Route::get('{id}', [ArticleController::class, 'showById']);
    Route::post('libelle', [ArticleController::class, 'showByLibelle']);

});


Route::prefix('dettes')->group(function () {
    Route::post('/', [DetteController::class, 'store']);
    Route::get('/', [DetteController::class, 'index']);
    Route::get('/{id}', [DetteController::class, 'show']);
    Route::post('/{id}/paiements', [DetteController::class, 'addPayment']);
    Route::get('/{id}/articles', [DetteController::class, 'getArticles']);
    Route::get('/{id}/paiements', [DetteController::class, 'getPayments']);
});
    Route::any('{segment}', function () {
        return response()->json([
            'error' => 'Invalid URL.'
        ]);
    })->where('segment', '.*');
});

// Route pour accès non autorisé
Route::get('unauthorized', function () {
    return response()->json([
        'error' => 'Unauthorized.'
    ], 401);
})->name('unauthorized');

