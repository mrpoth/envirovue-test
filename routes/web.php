<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::softDeletes('users', UserController::class);

Route::resource('users', UserController::class)->except(['create, store']);

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
