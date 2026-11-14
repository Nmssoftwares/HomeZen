<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Um\DashboardController;
use App\Http\Controllers\Um\UserController;

Route::controller(DashboardController::class)->group(function () {
   Route::get('/dashboard', 'index')->name('dashboard');
});

Route::get('admins', [UserController::class, 'admin'])->name('admins.index');

Route::controller(UserController::class)->prefix('users')->name('users.')->group(function () {
   Route::get('datatable', 'datatable')->name('datatable');
   Route::post('update-status', 'updateStatus')->name('update-status');
});

Route::resource('users', UserController::class)->parameters(['users' => 'id']);