<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Module;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
   public function index()
   {
      $user = Auth::user();

      // 1. Greeting calculation (IST timezone)
      $hour = now('Asia/Kolkata')->hour;
      $greeting = $hour < 12 ? 'Good morning' : ($hour < 17 ? 'Good afternoon' : 'Good evening');
      $userName = $user->name ?? trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? ''));

      // 2. Global Metric Counters
      $totalCompanies      = Company::count();
      $activeCompanies     = Company::where('status', 1)->count();
      $totalAdmins         = User::where('role', 2)->count();
      $totalUsers          = User::where('role', 3)->count();
      $totalActiveModules  = Module::where('status', 1)->count();
      $totalModules        = Module::count();

      // 3. Modules Grouped by Category & Ordered
      $groupedModules = Module::orderBy('group_order', 'asc')
         ->get()
         ->groupBy(fn($item) => $item->group_name ?: 'General Operations');

      // 4. Recently Onboarded Households/Companies (Latest 5)
      $recentCompanies = Company::with(['users' => fn($q) => $q->where('role', 2)])
         ->latest()
         ->take(5)
         ->get();

      $data = compact(
         'greeting',
         'userName',
         'totalCompanies',
         'activeCompanies',
         'totalAdmins',
         'totalUsers',
         'totalActiveModules',
         'totalModules',
         'groupedModules',
         'recentCompanies'
      );

      $data['title'] = 'SuperAdmin - Dashboard';

      return view('superadmin.dashboard', $data);
   }
}
