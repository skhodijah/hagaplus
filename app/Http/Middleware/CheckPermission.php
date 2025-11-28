<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$permissions): Response
    {
        $user = $request->user();

        if (!$user) {
            abort(403, 'Unauthorized');
        }

        // Instansi owner (system role id 2) has full access when not linked to an employee record
        if ($user->system_role_id === 2 && !$user->employee) {
            return $next($request);
        }

        // Check if user has employee record with instansi role
        if (!$user->employee || !$user->employee->instansiRole) {
            abort(403, 'No role assigned');
        }

        $role = $user->employee->instansiRole;

        // Check if role has any of the required permissions
        if (!$role->hasAnyPermission($permissions)) {
            abort(403, 'Insufficient permissions');
        }

        return $next($request);
    }
}
