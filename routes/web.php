<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\RoomController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Property Management Routes (Admin & Owner only)
    Route::middleware('admin')->group(function () {
        Route::resource('properties', PropertyController::class);
        Route::post('properties/{property}/toggle-publish', [PropertyController::class, 'togglePublish'])
            ->name('properties.toggle-publish');
        Route::post('properties/{property}/toggle-featured', [PropertyController::class, 'toggleFeatured'])
            ->name('properties.toggle-featured');
        
        // Room Management Routes (Admin & Owner only)
        Route::resource('rooms', RoomController::class);
        Route::post('rooms/{room}/change-status', [RoomController::class, 'changeStatus'])
            ->name('rooms.change-status');
    });
});

Route::middleware(['auth', 'role:owner'])->group(function () {
    Route::get('/dashboard/owner', fn() => 'Selamat datang, Bos');
});

Route::middleware(['auth', 'role:tenant'])->group(function () {
    Route::get('/dashboard/tenant', fn() => 'Halo penghuni!');
});


require __DIR__.'/auth.php';

