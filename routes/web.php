<?php

use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectInvitationController;
use App\Http\Controllers\SavingsDepositController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

// Google OAuth Routes
Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard & Projects
    Route::get('/dashboard', [ProjectController::class, 'index'])->name('dashboard');
    Route::post('/projects', [ProjectController::class, 'store'])->name('projects.store');
    Route::put('/projects/{project}', [ProjectController::class, 'update'])->name('projects.update');
    Route::post('/projects/{project}/complete', [ProjectController::class, 'complete'])->name('projects.complete');
    Route::delete('/projects/{project}', [ProjectController::class, 'destroy'])->name('projects.destroy');

    // Setoran & Verifikasi
    Route::post('/projects/{project}/deposit', [SavingsDepositController::class, 'store'])->name('deposits.store');
    Route::post('/deposits/{deposit}/verify', [SavingsDepositController::class, 'verify'])->name('deposits.verify');
    Route::post('/deposits/{deposit}/reject', [SavingsDepositController::class, 'reject'])->name('deposits.reject');
    Route::get('/projects/{project}/export-csv', [SavingsDepositController::class, 'exportCsv'])->name('projects.export-csv');

    // Pencarian User & Undangan
    Route::get('/users/search', [ProjectInvitationController::class, 'searchUsers'])->name('users.search');
    Route::post('/projects/{project}/invite', [ProjectInvitationController::class, 'store'])->name('projects.invite');
    Route::post('/invitations/{invitation}/accept', [ProjectInvitationController::class, 'accept'])->name('invitations.accept');
    Route::post('/invitations/{invitation}/decline', [ProjectInvitationController::class, 'decline'])->name('invitations.decline');

    // Profile Management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';