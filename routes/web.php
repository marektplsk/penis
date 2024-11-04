<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WinController;
use App\Http\Controllers\SearchController; 
use App\Http\Controllers\PortfolioController;
use App\Http\Controllers\AuthController; 
use Illuminate\Support\Facades\Auth;

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
Route::get('/dashboard/{id}', [WinController::class, 'show'])->name('dashboard.sh  ow'); // Show details for a specific entry
Route::get('/wins/{id}', [WinController::class, 'show'])->name('dashboard.show');
Route::get('/dashboard/wins/{id}', [WinController::class, 'show'])->name('dashboard.show');

Route::get('/dashboard/wins/{id}/edit', [WinController::class, 'edit'])->name('dashboard.edit'); // Route to show the edit form
Route::put('/dashboard/wins/{id}', [WinController::class, 'update'])->name('dashboard.update'); // Route to handle the update


Route::get('/search', [SearchController::class, 'search'])->name('search');

Route::get('/portfolio', [PortfolioController::class, 'index'])->name('portfolio.index');
Route::post('/portfolio', [PortfolioController::class, 'store'])->name('portfolio.store');

Route::delete('/portfolio/{id}', [PortfolioController::class, 'destroy'])->name('portfolio.destroy');
Route::resource('portfolio', PortfolioController::class)->only(['index', 'store', 'destroy', 'edit', 'update']);




Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


Route::middleware('auth')->group(function () {
    // Profile routes
    Route::get('/profile', [AuthController::class, 'showProfile'])->name('profile.show');
    Route::get('/profile/edit', [AuthController::class, 'editProfile'])->name('profile.edit');
    Route::post('/profile/update', [AuthController::class, 'updateProfile'])->name('profile.update');
});

Route::post('/logout', function () {
    Auth::logout();
    return redirect('/')->with('success', 'Successfully logged out.');
})->name('logout');
