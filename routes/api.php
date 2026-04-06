<?php

use App\Http\Controllers\Api\ProjectArtifactController;
use App\Http\Controllers\Api\ProjectController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('projects', ProjectController::class);
Route::apiResource('project-artifacts', ProjectArtifactController::class)
    ->parameters(['project-artifacts' => 'projectArtifact']);
