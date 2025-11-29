<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSubscription
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

        // Allow superadmin
        if ($user->hasRole('superadmin')) {
             return $next($request);
        }

        if (!$user->instansi) {
             return redirect()->route('login')->with('error', 'No instance associated.');
        }

        // Check if user has active subscription
        if ($user->hasActiveSubscription()) {
            return $next($request);
        }

        // Check if pending
        $pendingSubscription = $user->instansi->subscriptions()
            ->where('status', 'pending')
            ->latest()
            ->first();

        if ($pendingSubscription) {
            // Allow specific routes
            $allowedRoutes = [
                'admin.subscription.index',
                'admin.subscription.create',
                'admin.subscription.store',
                'admin.subscription.cancel-payment',
                'admin.company-profile.index',
                'admin.company-profile.update',
                'logout',
            ];
            
            $currentRoute = $request->route()->getName();

            if (in_array($currentRoute, $allowedRoutes) || 
                str_starts_with($currentRoute ?? '', 'admin.subscription.') || 
                str_starts_with($currentRoute ?? '', 'admin.company-profile.')) {
                return $next($request);
            }

            return redirect()->route('admin.subscription.index');
        }

        return redirect()->route('subscription.expired');
    }
}
