<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\CounselingSessionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Authentifizierte Routen
Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard - für alle authentifizierten Benutzer
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profil-Verwaltung
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Klienten - nur für Admin und Berater
    Route::middleware(['role:admin,counselor'])->group(function () {
        Route::resource('clients', ClientController::class);
        Route::resource('sessions', CounselingSessionController::class);
    });

    // Kalender - für alle authentifizierten Benutzer
    Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar.index');

    // Dokumente - nur für Admin und Berater
    Route::middleware(['role:admin,counselor'])->group(function () {
        Route::resource('documents', DocumentController::class);
    });

    // Admin-Bereich - nur für Administratoren
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

        // Benutzerverwaltung
        Route::get('/users', [AdminController::class, 'users'])->name('users');
        Route::get('/users/create', [AdminController::class, 'createUser'])->name('users.create');
        Route::post('/users', [AdminController::class, 'storeUser'])->name('users.store');
        Route::get('/users/{user}/edit', [AdminController::class, 'editUser'])->name('users.edit');
        Route::put('/users/{user}', [AdminController::class, 'updateUser'])->name('users.update');
        Route::delete('/users/{user}', [AdminController::class, 'deleteUser'])->name('users.destroy');

        // System-Einstellungen
        Route::get('/settings', [AdminController::class, 'systemSettings'])->name('settings');

        // Berichte
        Route::get('/reports', [AdminController::class, 'reports'])->name('reports');
    });
});

require __DIR__.'/auth.php';
