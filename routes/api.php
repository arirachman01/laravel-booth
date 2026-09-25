<?php

use App\Http\Controllers\FrameTemplateController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PhotoSessionController;
use App\Http\Controllers\SettingController;

Route::prefix('setting')->group(function () {
    Route::get('/', [SettingController::class, 'view']);
});

Route::prefix('frame-templates')->group(function () {
    Route::get('/', [FrameTemplateController::class, 'index']);
    Route::post('/', [FrameTemplateController::class, 'store']);
    Route::get('/{frameTemplate}', [FrameTemplateController::class, 'show']);
    Route::post('/{frameTemplate}', [FrameTemplateController::class, 'update']);
    Route::delete('/{frameTemplate}', [FrameTemplateController::class, 'destroy']);
});

Route::prefix('photo-sessions')->group(function () {
    Route::get('/', [PhotoSessionController::class, 'index']);
    Route::post('/', [PhotoSessionController::class, 'store']);

    Route::get('/{sessionId}', [
        PhotoSessionController::class,
        'show'
    ]);

    Route::post('/{sessionId}/results', [
        PhotoSessionController::class,
        'uploadResult'
    ]);

    Route::delete('/{sessionId}', [
        PhotoSessionController::class,
        'destroy'
    ]);
});

