<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
   /**
    * Show Login Page
    */
   public function index()
   {
      if (Auth::check()) {
         return $this->handleRoleRedirect(Auth::user());
      }

      return view('auth.login');
   }

   /**
    * Login Form Submit (AJAX)
    */
   public function login(Request $request): JsonResponse
   {
      $request->validate([
         'login_identifier' => 'required|string',
         'password'         => 'required|string',
      ]);

      $identifier = $request->login_identifier;

      // 1. Find User by email OR phone number
      $user = User::where('email', $identifier)
         ->orWhere('phone', $identifier)
         ->first();

      if (!$user) {
         return response()->json([
            'status'  => 'error',
            'message' => 'No account found with this email or phone number.',
         ]);
      }

      // 2. Check User Active Status
      if ((int) $user->status !== 1) {
         return response()->json([
            'status'  => 'error',
            'message' => 'Your user account has been deactivated. Please contact support.',
         ]);
      }

      // 3. Check Company Status (If not SuperAdmin)
      if ((int) $user->role !== User::ROLE_SUPER_ADMIN) {
         $company = Company::find($user->company_id);
         if (!$company || (int) $company->status !== 1) {
            return response()->json([
               'status'  => 'error',
               'message' => 'Your organization subscription is inactive or suspended.',
            ]);
         }
      }

      // 4. Verify Password
      if (!Hash::check($request->password, $user->password)) {
         return response()->json([
            'status'  => 'error',
            'message' => 'Incorrect password entered.',
         ]);
      }

      // 5. Authenticate Session & Timestamp
      Auth::login($user, $request->boolean('remember'));

      $user->update([
         'last_login_at' => now(),
      ]);

      return response()->json([
         'status'   => 'success',
         'message'  => 'Welcome back, ' . $user->first_name . '!',
         'redirect' => $this->getRedirectUrl($user),
      ]);
   }

   /**
    * Logout User
    */
   public function logout()
   {
      Auth::logout();
      request()->session()->invalidate();
      request()->session()->regenerateToken();

      return redirect('/login');
   }

   /**
    * Role Wise Redirection Resolver
    */
   private function getRedirectUrl(User $user): string
   {
      return match ((int) $user->role) {
         User::ROLE_SUPER_ADMIN   => url('/superadmin/dashboard'),
         User::ROLE_COMPANY_ADMIN => url('/admin/dashboard'),
         User::ROLE_USER          => url('/user/dashboard'),
         default                  => url('/'),
      };
   }

   private function handleRoleRedirect(User $user)
   {
      return redirect($this->getRedirectUrl($user));
   }
}
