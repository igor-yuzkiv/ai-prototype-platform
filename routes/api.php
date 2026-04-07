<?php

use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\ProjectPrototypeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('projects', ProjectController::class);

Route::post('projects/{project}/prototype/generate', [ProjectPrototypeController::class, 'generate']);
