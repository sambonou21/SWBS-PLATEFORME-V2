<?php

use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\CatalogApiController;
use App\Http\Controllers\Api\ChatApiController;
use App\Http\Controllers\Api\QuoteApiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes SWBS
|--------------------------------------------------------------------------
|
| API REST destinée à l'intégration avec d'autres systèmes (front JS,
| applications mobiles, etc.). Les endpoints publics ne nécessitent pas
| d'authentification, les endpoints sensibles peuvent être protégés
| plus tard par Sanctum si nécessaire.
|
*/

Route::prefix('v1')->group(function () {
    // Catalogue
    Route::get('/services', [CatalogApiController::class, 'services']);
    Route::get('/portfolio', [CatalogApiController::class, 'portfolio']);
    Route::get('/products', [CatalogApiController::class, 'products']);
    Route::get('/products/{slug}', [CatalogApiController::class, 'product']);

    // Devis
    Route::post('/quotes', [QuoteApiController::class, 'store']);

    // Auth simplifiée (JSON)
    Route::post('/auth/login', [AuthApiController::class, 'login']);
    Route::post('/auth/register', [AuthApiController::class, 'register']);

    // Chat temps réel (fallback HTTP)
    Route::post('/chat/start', [ChatApiController::class, 'start']);
    Route::post('/chat/message', [ChatApiController::class, 'send']);
    Route::get('/chat/conversation/{conversation}', [ChatApiController::class, 'fetch']);
});