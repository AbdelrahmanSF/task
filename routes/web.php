<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ServiceController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ServiceController::class, 'index'])->name('home');
Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
Route::get('/services/{service}', [ServiceController::class, 'show'])->name('services.show');

Route::get('/dashboard', function () {
    return match (true) {
        auth()->user()->isProvider() => redirect()->route('provider.bookings'),
        auth()->user()->isAdmin() => redirect()->route('admin.categories'),
        default => redirect()->route('customer.bookings'),
    };
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth','role:customer'])->group(function () {
    Route::post('/services/{service}/book', [BookingController::class, 'store'])->name('bookings.store');
    Route::get('/my-bookings', [BookingController::class, 'customer'])->name('customer.bookings');
});

Route::middleware(['auth','role:provider'])->group(function () {
    Route::get('/provider/services', [ServiceController::class, 'manage'])->name('provider.services');
    Route::get('/provider/services/create', [ServiceController::class, 'create'])->name('provider.services.create');
    Route::post('/provider/services', [ServiceController::class, 'store'])->name('provider.services.store');
    Route::get('/provider/services/{service}/edit', [ServiceController::class, 'edit'])->name('provider.services.edit');
    Route::put('/provider/services/{service}', [ServiceController::class, 'update'])->name('provider.services.update');
    Route::delete('/provider/services/{service}', [ServiceController::class, 'destroy'])->name('provider.services.destroy');
    Route::get('/provider/bookings', [BookingController::class, 'provider'])->name('provider.bookings');
    Route::patch('/provider/bookings/{booking}/status', [BookingController::class, 'status'])->name('provider.bookings.status');
});

Route::middleware(['auth','role:admin'])->group(function () {
    Route::get('/admin/categories', [CategoryController::class, 'index'])->name('admin.categories');
    Route::post('/admin/categories', [CategoryController::class, 'store'])->name('admin.categories.store');
    Route::put('/admin/categories/{category}', [CategoryController::class, 'update'])->name('admin.categories.update');
    Route::delete('/admin/categories/{category}', [CategoryController::class, 'destroy'])->name('admin.categories.destroy');
});

require __DIR__.'/auth.php';
