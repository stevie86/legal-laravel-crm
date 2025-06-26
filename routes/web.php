<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\CounselingSessionController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\CalendarController;

// Dashboard
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// Klienten
Route::resource('clients', ClientController::class);

// Beratungssitzungen
Route::resource('sessions', CounselingSessionController::class);

// Dokumente
Route::resource('documents', DocumentController::class);
Route::get('documents/{document}/download', [DocumentController::class, 'download'])->name('documents.download');

// Kalender
Route::get('calendar', [CalendarController::class, 'index'])->name('calendar.index');
Route::get('calendar/events', [CalendarController::class, 'events'])->name('calendar.events');
Route::post('calendar/events', [CalendarController::class, 'store'])->name('calendar.store');
Route::put('calendar/events/{event}', [CalendarController::class, 'update'])->name('calendar.update');
Route::delete('calendar/events/{event}', [CalendarController::class, 'destroy'])->name('calendar.destroy');
