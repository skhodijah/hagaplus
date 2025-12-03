<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Services\SubscriptionService;
use Illuminate\Support\Facades\View;

class CheckSubscriptionStatus
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (!$user || !$user->instansi) {
            return $next($request);
        }

        $subscriptionService = new SubscriptionService($user->instansi);
        $status = $subscriptionService->getSubscriptionStatus();
        
        // Share status with all views
        View::share('subscriptionStatus', $status);
        View::share('subscriptionDaysRemaining', $subscriptionService->getRemainingDays());

        // Employee Restrictions
        if ($user->hasRole('employee')) {
            if ($status === 'expired' || $status === 'suspended' || $status === 'canceled') {
                // Allow logout
                if ($request->routeIs('logout')) {
                    return $next($request);
                }
                
                // Allow viewing dashboard but block actions
                // Or maybe redirect to a specific "Service Unavailable" page
                // For now, let's block check-in/out specifically if that's the request
                if ($request->routeIs('employee.attendance.check-in') || $request->routeIs('employee.attendance.check-out')) {
                     return response()->json([
                        'success' => false,
                        'message' => 'Layanan tidak tersedia. Paket berlangganan instansi telah berakhir. Silakan hubungi Admin.'
                    ], 403);
                }
            }
        }

        // Admin Restrictions
        if ($user->hasRole('admin')) {
             if ($status === 'expired' || $status === 'suspended' || $status === 'canceled') {
                // Allow logout and subscription management pages
                $allowedRoutes = [
                    'logout',
                    'admin.subscription.index',
                    'admin.subscription.upgrade',
                    'admin.subscription.extend',
                    // Add payment routes here
                ];

                if (!in_array($request->route()->getName(), $allowedRoutes) && !$request->isMethod('get')) {
                    // Block POST/PUT/DELETE actions for expired admins except on allowed routes
                    // This makes the admin panel effectively read-only
                    return back()->with('error', 'Paket berlangganan telah berakhir. Silakan perpanjang atau upgrade paket untuk melanjutkan.');
                }
            }
        }

        return $next($request);
    }
}
