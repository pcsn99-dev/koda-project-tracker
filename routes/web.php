<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectController;

Route::inertia('/', 'Welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'Dashboard')->name('dashboard');
});



//projects routes

Route::middleware('auth')->group(function () {
    Route::apiResource('projects', ProjectController::class);
});

require __DIR__.'/settings.php';
