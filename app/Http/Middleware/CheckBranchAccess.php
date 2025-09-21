<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckBranchAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();
        $branchId = $request->route('branch') ?? $request->get('branch_id');

        // Check if user has access to the requested branch
        if ($branchId && !$user->hasAccessToBranch($branchId)) {
            abort(403, 'You do not have access to this branch.');
        }

        return $next($request);
    }
}
