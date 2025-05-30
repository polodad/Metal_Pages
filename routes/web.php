<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\ListingController; // ← Importa tu controlador

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin'       => Route::has('login'),
        'canRegister'    => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion'     => PHP_VERSION,
    ]);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    // List all listings
    Route::get('/listings', [ListingController::class, 'index'])
        ->name('listings.index');

    // Show form to create a new listing
    Route::get('/listings/create', [ListingController::class, 'create'])
        ->name('listings.create');

    // Persist new listing
    Route::post('/listings', [ListingController::class, 'store'])
        ->name('listings.store');
});
