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
        Route::post('', 'store')->name('store');
        Route::post('normalize-requirements', 'normalizeRequirements')->name('normalize-requirements');
        Route::get('{prototype}', 'show')->name('show');
        Route::delete('{prototype}', 'destroy')->name('destroy');
        Route::post('{prototype}/generate-plan', 'generatePlan')->name('generate-plan');
        Route::post('{prototype}/publish', 'publish')->name('publish');
    }
);

// Route::post('prototypes/{prototype}/prototype/generate', [PrototypeGeneratorController::class, 'generate']);

// Route::post('/test-broadcast', function (Request $request) {
//    logger()->info($request->all());
//    $message = $request->string('message', 'Hello from Reverb!')->toString();
//    broadcast(new TestBroadcastEvent($message));
//
//    return response()->json(['message' => $message]);
// });
