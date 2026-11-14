<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SuperAdmin\DashboardController as SuperAdminDashboardController;
use App\Http\Controllers\SuperAdmin\ModuleController as SuperAdminModuleController;
use App\Http\Controllers\SuperAdmin\CompanyController as SuperAdminCompanyController;
use App\Http\Controllers\SuperAdmin\ProfileController as SuperAdminProfileController;

// Dashboard
Route::controller(SuperAdminDashboardController::class)->group(function () {
    Route::get('/dashboard', 'index')->name('dashboard');
});

// Modules
Route::controller(SuperAdminModuleController::class)->group(function () {
    Route::get('/modules', 'index')->name('modules');
    Route::get('/modules-datatable', 'datatable')->name('modules.datatable');
    Route::post('/modules/update-status', 'updateStatus')->name('modules.update-status');
    Route::get('modules/export-pdf', 'exportPdf')->name('modules.export.pdf');
});

// Companies
Route::controller(SuperAdminCompanyController::class)->group(function () {
    Route::get('/companies-datatable', 'datatable')->name('companies.datatable');
    Route::post('/companies-reset-password', 'resetPassword')->name('companies.reset.password');
    Route::post('/companies-get-modules', 'getCompanyModules')->name('companies.get.modules');
    Route::post('/companies-update-modules', 'updateCompanyModules')->name('companies.update.modules');
    Route::get('companies/export-pdf', 'exportPdf')->name('companies.export.pdf');
});
Route::resource('companies', SuperAdminCompanyController::class)->names('companies');

// Profile
Route::controller(SuperAdminProfileController::class)->group(function () {
    Route::get('/profile', 'index')->name('profile');
    Route::post('/profile-reset-password', 'resetPassword')->name('profile.reset.password');
    Route::post('/profile-update', 'updateProfile')->name('profile.update');
    Route::post('/profile-image-remove', 'removeProfileImage')->name('profile.image.remove');
    Route::post('/profile-image-upload', 'uploadImage')->name('profile.image.upload');
});