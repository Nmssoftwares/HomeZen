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
      $data['title'] = 'SuperAdmin - Dashboard';
      $user          = Auth::user();

      // 1. Greeting calculation
      $hour = now('Asia/Kolkata')->hour;
      $data['greeting'] = $hour < 12 ? 'Good morning' : ($hour < 17 ? 'Good afternoon' : 'Good evening');
      $data['userName'] = $user->name ?? ($user->first_name . ' ' . $user->last_name);

      // 2. Global Metric Counters
      $data['totalCompanies']     = Company::count();
      $data['activeCompanies']    = Company::where('status', 1)->count();
      $data['totalAdmins']        = User::where('role', 2)->count();
      $data['totalUsers']         = User::where('role', 3)->count();
      $data['totalActiveModules'] = Module::where('status', 1)->count();
      $data['totalModules']       = Module::count();

      // 3. Modules Grouped by Category & Ordered
      $data['groupedModules'] = Module::orderBy('group_order')
         ->get()
         ->groupBy(fn($item) => $item->group_name ?: 'General Operations');

      // 4. Recently Onboarded Households/Companies (Latest 5)
      $data['recentCompanies'] = Company::with(['users' => fn($q) => $q->where('role', 2)])
         ->latest()
         ->take(5)
         ->get();

      return view('superadmin.dashboard', $data);
   }
}
