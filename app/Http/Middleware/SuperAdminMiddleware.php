<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SuperAdminMiddleware
{
   /**
    * Handle an incoming request.
    */
   public function handle(Request $request, Closure $next): Response
   {
      if (Auth::check() && Auth::user()->isSuperAdmin()) {
         return $next($request);
      }

      if ($request->ajax() || $request->wantsJson()) {
         return response()->json(['status' => 'error', 'message' => 'Root SuperAdmin privileges required.'], 403);
      }

      abort(403, 'Unauthorized Access: SuperAdmin privileges required.');
   }
}
