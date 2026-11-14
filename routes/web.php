<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
/*
|--------------------------------------------------------------------------
| Public Authentication Routes
|--------------------------------------------------------------------------
*/

Route::middleware([''])->group(function () {
   Route::controller(LoginController::class)->group(function () {
      Route::get('/login', 'index')->name('login');
      Route::post('/login-submit', 'login')->middleware('guest')->name('login.submit');
      Route::get('/logout', 'logout')->name('logout');
   });
});

/*
|--------------------------------------------------------------------------
| Core System & Role-Based Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->prefix('superadmin')->name('superadmin.')->group(base_path('routes/modules/superadmin.php'));
Route::middleware(['auth'])->prefix('um')->name('um.')->group(base_path('routes/modules/um.php'));

Route::get('/language/{lang}', function ($lang) {
   if (in_array($lang, ['en', 'hi', 'gu'])) {
      session(['locale' => $lang]);
   }
   return redirect()->back();
})->name('language.change');
