<?php

use App\Http\Controllers\PrototypeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::group(
    [
        'prefix'     => 'prototypes',
        'controller' => PrototypeController::class,
        'as'         => 'prototypes.',
    ],
    function () {
        Route::get('', 'index')->name('index');
        Route::post('', 'create')->name('create');
        Route::post('normalize-requirements', 'normalizeRequirements')->name('normalize-requirements');
        Route::get('{prototype}', 'show')->name('show');
        Route::delete('{prototype}', 'destroy')->name('destroy');
        Route::post('{prototype}/publish', 'publish')->name('publish');
        Route::post('{prototype}/implement-plan', 'implementPlan')->name('implement-plan');
    }
);
