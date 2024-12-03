<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JsonController;
use App\Http\Controllers\HelloWorldController;

// Rutas para el JsonController
Route::prefix('json')->group(function () {
    Route::get('/', [JsonController::class, 'index']); // Listar los archivos JSON
    Route::post('/', [JsonController::class, 'store']); // Guardar un archivo JSON
    Route::get('/{filename}', [JsonController::class, 'show']); // Ver el contenido de un archivo JSON
    Route::put('/{filename}', [JsonController::class, 'update']); // Actualizar un archivo JSON
    Route::delete('/{filename}', [JsonController::class, 'destroy']); // Eliminar un archivo JSON
});

// Rutas para el HelloWorldController
Route::apiResource('hello', HelloWorldController::class); // CRUD completo para el controlador HelloWorldController

