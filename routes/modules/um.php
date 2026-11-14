<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Um\DashboardController;
use App\Http\Controllers\Um\UserController;

// 1. Dashboard
Route::controller(DashboardController::class)->group(function () {
   Route::get('/dashboard', 'index')->name('dashboard');
});

// 2. Admin Listing Route (Role = 2)
Route::get('admins', [UserController::class, 'admin'])->name('admins.index');

Route::prefix('users')->name('users.')->controller(UserController::class)->group(function () {
   Route::get('datatable', 'datatable')->name('datatable');
   Route::post('update-status', 'updateStatus')->name('update-status');
});

Route::resource('users', UserController::class)->parameters(['users' => 'id']);