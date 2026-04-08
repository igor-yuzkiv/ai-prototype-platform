<?php

use App\Http\Controllers\PrototypeController;
use App\Http\Controllers\PrototypeGeneratorController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('prototypes', PrototypeController::class)->except(['update']);

Route::post('prototypes/{prototype}/prototype/generate', [PrototypeGeneratorController::class, 'generate']);
