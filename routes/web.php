<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\ListingController;

// Ruta de bienvenida
Route::get('/', function () {
    return redirect()->route('listings.index');
})->name('home');

// Rutas protegidas con autenticación
Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    // Rutas de listados
    Route::get('/listings', [ListingController::class, 'index'])->name('listings.index');
    Route::get('/listings/create', [ListingController::class, 'create'])->name('listings.create');
    Route::post('/listings', [ListingController::class, 'store'])->name('listings.store');
    Route::delete('/listings/{listing}', [ListingController::class, 'destroy'])->name('listings.destroy');
});
