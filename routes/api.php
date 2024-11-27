<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\PilotController;
use App\Http\Controllers\AuthController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('teams')->group(function () {
    Route::get('/', [TeamController::class, 'index']);
    Route::post('/', [TeamController::class, 'store']);
    Route::get('/search', [TeamController::class, 'search']);
    Route::get('/{team}/pilots', [TeamController::class, 'pilots']);
    Route::get('/{team}/statistics', [TeamController::class, 'statistics']);
    Route::get('/{team}', [TeamController::class, 'show']);
    Route::put('/{team}', [TeamController::class, 'update']);
    Route::delete('/{team}', [TeamController::class, 'destroy']);
});

Route::prefix('pilots')->group(function () {
    Route::get('/', [PilotController::class, 'index']);
    Route::post('/', [PilotController::class, 'store']); 
    Route::get('/{pilot}', [PilotController::class, 'show']);
    Route::put('/{pilot}', [PilotController::class, 'update']);
    Route::delete('/{pilot}', [PilotController::class, 'destroy']);
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);
