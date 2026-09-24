<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PhotoSessionController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('photo-sessions',[PhotoSessionController::class, "view"]);
Route::post('photo-sessions',[PhotoSessionController::class, "store"])->name("file.upload.store");