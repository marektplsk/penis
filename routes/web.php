<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WinController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\PortfolioController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

// Redirect root URL to the app index
Route::get('/', function() {
    if (Cookie::get('was_logged_in')) {
        return redirect()->route('loginWelcome');
    }
    return redirect()->route('welcome');
});

// Welcome route
Route::get('/welcome', function () {
    return view('welcome.welcome');
})->name('welcome');

// Define the app index route with authentication check
Route::get('/app', function () {
    if (!Auth::check()) {
        return redirect()->route('loginWelcome');
    }
    return app(WinController::class)->index();
})->name('app.index');

Route::post('/app', [WinController::class, 'store'])->name('app.store');

// Use resource routing for wins, which includes index, create, store, show, edit, update, destroy
Route::resource('wins', WinController::class);

// Dashboard routes
Route::get('/dashboard', [WinController::class, 'dashboard'])->name('dashboard');
Route::get('/dashboard/{id}', [WinController::class, 'show'])->name('dashboard.show');
Route::get('/wins/{id}', [WinController::class, 'show'])->name('dashboard.show');
Route::get('/dashboard/wins/{id}', [WinController::class, 'show'])->name('dashboard.show');
Route::get('/dashboard/wins/{id}/edit', [WinController::class, 'edit'])->name('dashboard.edit');
Route::put('/dashboard/wins/{id}', [WinController::class, 'update'])->name('dashboard.update');

// Search route
Route::get('/search', [SearchController::class, 'search'])->name('search');

// Portfolio routes
Route::get('/portfolio', [PortfolioController::class, 'index'])->name('portfolio.index');
Route::post('/portfolio', [PortfolioController::class, 'store'])->name('portfolio.store');
Route::delete('/portfolio/{id}', [PortfolioController::class, 'destroy'])->name('portfolio.destroy');
Route::resource('portfolio', PortfolioController::class)->only(['index', 'store', 'destroy', 'edit', 'update']);

// Authentication routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected profile routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [AuthController::class, 'showProfile'])->name('profile.show');
    Route::get('/profile/edit', [AuthController::class, 'editProfile'])->name('profile.edit');
    Route::post('/profile/update', [AuthController::class, 'updateProfile'])->name('profile.update');
});

// Custom logout route redirecting to loginWelcome
Route::post('/logout', function () {
    Auth::logout();
    return redirect()->route('loginWelcome')->with('success', 'Successfully logged out.');
})->name('logout');

// Custom loginWelcome route
Route::get('/loginWelcome', function () {
    return view('welcome.loginWelcome');
})->name('loginWelcome');

