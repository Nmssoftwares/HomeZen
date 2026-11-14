<?php

namespace App\Http\Controllers\Um;

use App\Http\Controllers\Controller;
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

      // 2. UM Specific Metric Counters (Sirf Admins & Users)
      $totalAdmins       = User::where('role', 2)->count();
      $totalUsers        = User::where('role', 3)->count();
      $activeUsersCount  = User::where('status', 1)->count();
      $inactiveUsersCount = User::where('status', 0)->count();

      // 3. Modules Grouped for UM Workspace
      $groupedModules = Module::where('group_name', 'LIKE', '%Access%')
         ->orWhere('group_name', 'LIKE', '%User%')
         ->orderBy('group_order', 'asc')
         ->get()
         ->groupBy(fn($item) => $item->group_name ?: 'User Access & Management');

      if ($groupedModules->isEmpty()) {
         $groupedModules = Module::orderBy('group_order', 'asc')
            ->get()
            ->groupBy(fn($item) => $item->group_name ?: 'General Operations');
      }

      $data = compact(
         'greeting',
         'userName',
         'totalAdmins',
         'totalUsers',
         'activeUsersCount',
         'inactiveUsersCount',
         'groupedModules'
      );

      $data['title'] = 'UM - Dashboard';

      return view('um.dashboard', $data);
   }
}