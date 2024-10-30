<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WinController;

// Redirect root URL to the app index
Route::get('/', function () {
    return redirect()->route('app.index'); // Redirect to the app.index route
});

// Define the app index route
Route::get('/app', [WinController::class, 'index'])->name('app.index');
Route::post('/app', [WinController::class, 'store'])->name('app.store');

// Use resource routing for wins, which includes index, create, store, show, edit, update, destroy
Route::resource('wins', WinController::class);
