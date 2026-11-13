<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UserMiddleware
{
   /**
    * Handle an incoming request.
    */
   public function handle(Request $request, Closure $next): Response
   {
      if (Auth::check()) {
         $user = Auth::user();

         // Allow Standard Employee (Role 3) and Manager/Team Lead (Role 4)
         if ($user->isStandardUser() || $user->isManager()) {
            return $next($request);
         }
      }

      if ($request->ajax() || $request->wantsJson()) {
         return response()->json(['status' => 'error', 'message' => 'Unauthorized Employee Portal Access.'], 403);
      }

      abort(403, 'Unauthorized Access.');
   }
}
