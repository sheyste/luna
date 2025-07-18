<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('dashboard');
    })->name('dashboard');
});

Route::middleware(['auth', 'verified'])->get('/inventory', function () {
    return Inertia::render('Inventory');
})->name('inventory');

Route::middleware(['auth', 'verified'])->get('/production', function () {
    return Inertia::render('Production');
})->name('production');


require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
