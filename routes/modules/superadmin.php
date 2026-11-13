<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SuperAdmin\DashboardController as SuperAdminDashboardController;
use App\Http\Controllers\SuperAdmin\ModuleController as SuperAdminModuleController;
use App\Http\Controllers\SuperAdmin\CompanyController as SuperAdminCompanyController;
use App\Http\Controllers\SuperAdmin\ProfileController as SuperAdminProfileController;
use App\Http\Controllers\SuperAdmin\UserController as SuperAdminUserController;
use App\Http\Controllers\SuperAdmin\CompanySettingController as SuperAdminCompanySettingController;
use App\Http\Controllers\SuperAdmin\WebsiteController as SuperAdminWebsiteController;
use App\Http\Controllers\SuperAdmin\DemoRequestController as SuperAdminDemoRequestController;

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

// Company Settings (SuperAdmin Self Company Profile & Tax Info)
Route::controller(SuperAdminCompanySettingController::class)->prefix('company-settings')->name('company_settings.')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::post('/update', 'update')->name('update');
});

// Profile
Route::controller(SuperAdminProfileController::class)->group(function () {
    Route::get('/profile', 'index')->name('profile');
    Route::post('/profile-reset-password', 'resetPassword')->name('profile.reset.password');
    Route::post('/profile-update', 'updateProfile')->name('profile.update');
    Route::post('/profile-image-remove', 'removeProfileImage')->name('profile.image.remove');
    Route::post('/profile-image-upload', 'uploadImage')->name('profile.image.upload');
});

// User
Route::controller(SuperAdminUserController::class)->group(function () {
    Route::get('/user-datatable', 'datatable')->name('user.datatable');
    Route::post('/user-reset-password', 'resetPassword')->name('user.reset.password');
    Route::post('/user-get-modules', 'getUserModules')->name('user.get.modules');
    Route::post('/user-update-modules', 'updateUserModules')->name('user.update.modules');
    Route::get('user/export-pdf', 'exportPdf')->name('user.export.pdf');
    Route::get('/user/{id}/permissions', 'permissions')->name('user.permissions');
    Route::post('/user/{id}/permissions', 'updatePermissions')->name('user.permissions.update');
});
Route::resource('user', SuperAdminUserController::class)->names('user');

// Website (edit the public Offnexa site)
Route::controller(SuperAdminWebsiteController::class)->prefix('website')->name('website.')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/page/{page}', 'page')->where('page', '[a-z_]+')->name('page');
    Route::post('/page/{page}/settings', 'saveSettings')->where('page', '[a-z_]+')->name('settings');
    Route::post('/section/{section}/items', 'storeItem')->where('section', '[a-z_]+')->name('item.store');
    Route::put('/items/{item}', 'updateItem')->name('item.update');
    Route::post('/items/{item}/toggle', 'toggleItem')->name('item.toggle');
    Route::delete('/items/{item}', 'destroyItem')->name('item.destroy');
});

// Demo requests (from the public "Book a demo" form)
Route::controller(SuperAdminDemoRequestController::class)->prefix('demo-requests')->name('demo-requests.')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::post('/{demoRequest}/status', 'updateStatus')->name('status');
    Route::delete('/{demoRequest}', 'destroy')->name('destroy');
});