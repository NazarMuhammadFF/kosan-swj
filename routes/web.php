<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoomController;

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
    Route::get('/', fn() => redirect('/rooms'));
Route::resource('rooms', RoomController::class)->only(['index','show'])->middleware('auth');
});

Route::middleware(['auth', 'role:owner'])->group(function () {
    Route::get('/dashboard/owner', fn() => 'Selamat datang, Bos');
});

Route::middleware(['auth', 'role:tenant'])->group(function () {
    Route::get('/dashboard/tenant', fn() => 'Halo penghuni!');
});


require __DIR__.'/auth.php';
