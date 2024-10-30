<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WinController;
use App\Http\Controllers\SearchController; 

// Redirect root URL to the app index
Route::get('/', function () {
    return redirect()->route('app.index'); // Redirect to the app.index route
});

// Define the app index route
Route::get('/app', [WinController::class, 'index'])->name('app.index');
Route::post('/app', [WinController::class, 'store'])->name('app.store');

// Use resource routing for wins, which includes index, create, store, show, edit, update, destroy
Route::resource('wins', WinController::class);

Route::get('/dashboard', [WinController::class, 'dashboard'])->name('dashboard'); // Main dashboard page
Route::get('/dashboard/{id}', [WinController::class, 'show'])->name('dashboard.show'); // Show details for a specific entry
Route::get('/wins/{id}', [WinController::class, 'show'])->name('dashboard.show');
Route::get('/dashboard/wins/{id}', [WinController::class, 'show'])->name('dashboard.show');


Route::get('/search', [SearchController::class, 'search'])->name('search');
