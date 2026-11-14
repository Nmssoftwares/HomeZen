<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Um\DashboardController;

// Dashboard
Route::controller(DashboardController::class)->group(function () {
    Route::get('/dashboard', 'index')->name('dashboard');
});