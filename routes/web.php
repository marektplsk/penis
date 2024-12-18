<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WinController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\PortfolioController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use App\Http\Controllers\ChatController;


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

// Define the app index route with authentication and `was_logged_in` cookie check
Route::get('/app', function () {
    // Check if the user is logged in
    if (Auth::check()) {
        return app(WinController::class)->index(); // Allow access to /app
    }

    // If not logged in, check if the `was_logged_in` cookie is set to true
    if (Cookie::get('was_logged_in')) {
        return redirect()->route('loginWelcome'); // Redirect to loginWelcome if cookie is true
    } else {
        return redirect()->route('registerWelcome'); // Redirect to registerWelcome if cookie is false or missing
    }
})->name('app.index');

// Define the registerWelcome route
Route::get('/registerWelcome', function () {
    return view('welcome.registerWelcome');
})->name('registerWelcome');

Route::post('/app', [WinController::class, 'store'])->name('app.store');

Route::resource('wins', WinController::class);

// Dashboard routes
Route::get('/dashboard', [WinController::class, 'dashboard'])->name('dashboard');

// Primary route for showing a specific win record in the dashboard context
Route::get('/dashboard/{id}', [WinController::class, 'show'])->name('dashboard.show');

// Alternative dashboard route for showing a win (if required separately)
Route::get('/dashboard/wins/{id}', [WinController::class, 'show'])->name('dashboard.wins.show');

Route::get('/dashboard/{id}/edit', [WinController::class, 'edit'])->name('dashboard.edit');

Route::put('dashboard/{id}', [WinController::class, 'update'])->name('wins.update');





// Search route
Route::get('/search', [SearchController::class, 'search'])->name('search');

// Portfolio routes
// Portfolio routes
Route::get('/portfolio', [PortfolioController::class, 'index'])->name('portfolio.index');
Route::post('/portfolio', [PortfolioController::class, 'store'])->name('portfolio.store');
Route::resource('portfolio', PortfolioController::class)->only(['index', 'store', 'destroy', 'edit', 'update']);
Route::delete('/portfolio/{id}', [PortfolioController::class, 'destroy'])->name('portfolio.destroy');



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

Route::get('/tags', [WinController::class, 'getTags']);
Route::post('/tags', [WinController::class, 'storeTag']);

Route::get('/chat/generate-feedback', [ChatController::class, 'generateFeedback']);
Route::post('/chat/send-message', [ChatController::class, 'sendMessage']);



