<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class CheckModuleAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $moduleShortName (e.g. 'ATS', 'VPM', 'AM', 'PRL', 'ATM')
     */
    public function handle(Request $request, Closure $next, string $moduleShortName): Response
    {
        // 1. Check user login
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // 2. SuperAdmin (Role 1) universal bypass
        if (method_exists($user, 'isSuperAdmin') ? $user->isSuperAdmin() : (int) $user->role === 1) {
            return $next($request);
        }

        $companyId = $user->company_id;
        $targetShortName = strtoupper(trim($moduleShortName));

        // 3. Step 1: Verify Company Subscription (assign_modules)
        $companyHasModule = DB::table('assign_modules')
            ->join('modules', 'assign_modules.module_id', '=', 'modules.id')
            ->where('assign_modules.company_id', $companyId)
            ->where('modules.status', 1)
            ->whereRaw('UPPER(modules.short_name) = ?', [$targetShortName])
            ->exists();

        if (!$companyHasModule) {
            return $this->denyAccess($request, $moduleShortName, "The '{$moduleShortName}' module is not subscribed or is inactive for your organization.");
        }

        // 4. Company Admin (Role 2) has access to all company subscribed modules
        if ((int) $user->role === 2) {
            return $next($request);
        }

        // 5. Step 2: Verify User-Specific Permission (Role 3 & Role 4 via user_modules)
        $userHasPermission = DB::table('user_modules')
            ->join('modules', 'user_modules.module_id', '=', 'modules.id')
            ->where('user_modules.company_id', $companyId)
            ->where('user_modules.user_id', $user->id)
            ->where('modules.status', 1)
            ->whereRaw('UPPER(modules.short_name) = ?', [$targetShortName])
            ->exists();

        if (!$userHasPermission) {
            return $this->denyAccess($request, $moduleShortName, "You do not have permission to access the '{$moduleShortName}' workspace. Please contact your company administrator.");
        }

        return $next($request);
    }

    /**
     * Standardized Access Denied Response
     */
    protected function denyAccess(Request $request, string $moduleShortName, string $message): Response
    {
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'status'  => 'error',
                'message' => $message,
            ], 403);
        }

        abort(403, $message);
    }
}