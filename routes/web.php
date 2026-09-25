<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PhotoSessionController;
use App\Http\Controllers\SettingController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $setting = \App\Models\Setting::find(1);

    return view('dashboard', compact('setting'));
})->name('dashboard');

Route::put('/settings', [SettingController::class, 'update'])
    ->name('settings.update');

Route::get('/results', [PhotoSessionController::class, 'view']);