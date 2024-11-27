<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PilotController;
use App\Http\Controllers\TeamController;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('pilots', PilotController::class);
Route::resource('teams', TeamController::class);
