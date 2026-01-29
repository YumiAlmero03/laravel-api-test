<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// API Routes
Route::post('/auth/token', [AuthController::class, 'issueToken']);

Route::middleware('auth:sanctum')->group(function () {
    Route::group(['prefix' => 'tags'], function () {
        Route::group(['prefix' => 'translations'], function () {
            Route::post('/', [TranslationController::class, 'store']);
            Route::put('/{translation}', [TranslationController::class, 'update']);
            Route::get('/{translation}', [TranslationController::class, 'show']);

            Route::get('/search', [TranslationController::class, 'search']);
            Route::get('/export', [TranslationExportController::class, 'export']);
        });

        Route::group(['prefix' => 'tags'], function () {
            Route::get('/', [TagController::class, 'index']);
            Route::get('/{id}', [TagController::class, 'show']);
            Route::post('', [TagController::class, 'store']);
            Route::put('/{id}', [TagController::class, 'update']);
            Route::delete('/tags/{id}', [TagController::class, 'destroy']);
        });

        Route::group(['prefix' => 'locales'], function () {
            Route::get('/', [LocaleController::class, 'index']);
            Route::get('/{id}', [TagController::class, 'show']);
            Route::post('/', [LocaleController::class, 'store']);
            Route::put('/{locale}', [LocaleController::class, 'update']);
            Route::delete('/{locale}', [LocaleController::class, 'destroy']);
        });
    });
});
