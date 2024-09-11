<?php
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


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
        Route::get('/', [ClientController::class, 'index']);
    });
    // Route pour les users
    Route::prefix('users')->group(function () {
        Route::post('/', [UserController::class, 'store']);
        Route::put('/{id}', [UserController::class, 'update']);
        Route::delete('/{id}', [UserController::class, 'destroy']);
        Route::get('/', [UserController::class, 'index']);
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