<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\CollaboratorController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\TransferController;
use App\Http\Middleware\AuditLogMiddleware;
use Illuminate\Support\Facades\Route;

Route::post('/registro', [AuthController::class, 'register']);
Route::post('/acesso', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/colaboradores/{id}', [CollaboratorController::class, 'show']);
    Route::get('/colaboradores/{id}/historico', [CollaboratorController::class, 'history']);
    Route::match(['put','patch'], '/colaboradores/{id}/localizacao', [CollaboratorController::class, 'updateLocation']);

    Route::post('/ativos', [AssetController::class, 'store'])->middleware(AuditLogMiddleware::class);
    Route::post('/transferencia', [TransferController::class, 'store'])->middleware(AuditLogMiddleware::class);

    Route::get('/relatorios', [ReportsController::class, 'index']);
});

