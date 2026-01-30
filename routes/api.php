<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\TranslationController;
use App\Http\Controllers\TranslationExportController;
use App\Http\Controllers\LocaleController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/login', [AuthController::class, 'issueToken']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::group(['prefix' => 'translations'], function () {
        Route::post('/', [TranslationController::class, 'store']);
        Route::put('/{translation}', [TranslationController::class, 'update']);
        Route::get('/{translation}', [TranslationController::class, 'show']);

        Route::get('/search', [TranslationController::class, 'search']);
        Route::get('/export', [TranslationExportController::class, 'export']);
    });

    Route::group(['prefix' => 'tags'], function () {
        Route::get('/', [TagController::class, 'index']); //list tags with optional search and pagination
        Route::post('', [TagController::class, 'store']);
        Route::get('/{id}', [TagController::class, 'show']); //get single tag by id
        Route::put('/{id}', [TagController::class, 'update']);
        Route::delete('/{id}', [TagController::class, 'destroy']);
    });

    Route::group(['prefix' => 'locales'], function () {
        Route::get('/', [LocaleController::class, 'index']);
        Route::get('/{locale}', [LocaleController::class, 'show']);
        Route::post('/', [LocaleController::class, 'store']);
        Route::put('/{locale}', [LocaleController::class, 'update']);
        Route::delete('/{locale}', [LocaleController::class, 'destroy']);
    });
});
