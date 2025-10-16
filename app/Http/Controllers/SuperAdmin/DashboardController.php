<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\SuperAdmin\Instansi;
use App\Models\SuperAdmin\Package;
use App\Models\SuperAdmin\Subscription;
use App\Models\Core\User;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->get('period', '30d');
        $since = match ($period) {
            '7d' => now()->subDays(7),
            '90d' => now()->subDays(90),
            '1y' => now()->subYear(),
            default => now()->subDays(30),
        };

        // Overview metrics
        $totalActiveCompanies = Instansi::where('status_langganan', 'active')->count();
        $newCompanies7d = Instansi::where('created_at', '>=', now()->subDays(7))->count();
        $expiring30d = Subscription::where('status', 'active')
            ->whereBetween('end_date', [now(), now()->addDays(30)])
            ->count();

        // Active users: prefer users.is_active if exists, otherwise count all users
        $activeUsers = Schema::hasColumn('users', 'is_active')
            ? DB::table('users')->where('is_active', 1)->count()
            : User::count();

        // Revenue (use subscription_history if table exists)
        $thisMonthRevenue = Schema::hasTable('subscription_history')
            ? DB::table('subscription_history')
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('amount')
            : 0;
        $lastMonthRevenue = Schema::hasTable('subscription_history')
            ? DB::table('subscription_history')
                ->whereMonth('created_at', now()->subMonth()->month)
                ->whereYear('created_at', now()->subMonth()->year)
                ->sum('amount')
            : 0;
        $mrrGrowthPct = $lastMonthRevenue > 0
            ? round((($thisMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100, 1) . '%'
            : '—';
        $thisMonthRevenueFormatted = $thisMonthRevenue > 0 ? 'Rp ' . number_format($thisMonthRevenue, 0, ',', '.') : '—';

        // Top stats used previously
        $totalInstansi = Instansi::count();
        $totalPackages = Package::count();
        $activeSubscriptions = Subscription::where('status', 'active')->count();
        $totalSubscriptions = Subscription::count();

        // Recent data
        $recentInstansi = Instansi::latest()->take(5)->get();
        $recentPackages = Package::latest()->take(5)->get();
        $recentSubscriptions = Subscription::with(['instansi', 'package'])
            ->latest()
            ->take(5)
            ->get();

        // Subscription stats for analytics
        $subscriptionStats = [
            'active' => Subscription::where('status', 'active')->count(),
            'inactive' => Subscription::where('status', 'inactive')->count(),
            'expired' => Subscription::where('status', 'expired')->count(),
        ];

        // Package distribution among active subscriptions
        $packageDistribution = Subscription::select('package_id', DB::raw('count(*) as total'))
            ->where('status', 'active')
            ->groupBy('package_id')
            ->with('package')
            ->get();

        // Monthly subscription counts in selected period
        $monthlySubscriptionCounts = Subscription::select(
            DB::raw('DATE_FORMAT(created_at, "%Y-%m") as ym'),
            DB::raw('count(*) as total')
        )
            ->where('created_at', '>=', $since)
            ->groupBy('ym')
            ->orderBy('ym')
            ->get();

        // Monthly instansi creation counts for the last 6 months (for growth chart)
        $monthlyInstansiCounts = Instansi::select(
            DB::raw('DATE_FORMAT(created_at, "%Y-%m") as ym'),
            DB::raw('count(*) as total')
        )
            ->where('created_at', '>=', now()->subMonths(5)->startOfMonth())
            ->groupBy('ym')
            ->orderBy('ym')
            ->get();

        // Monthly revenue data for the last 12 months
        $monthlyRevenue = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $revenue = DB::table('subscription_requests')
                ->where('payment_status', 'paid')
                ->whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->sum('amount');
            $monthlyRevenue[] = [
                'month' => $date->format('M Y'),
                'revenue' => $revenue
            ];
        }

        // Use actual data or empty if no subscriptions

        // Use actual data or empty if no packages

        // Financial summaries
        $pendingPayments = Schema::hasTable('subscription_history')
            ? DB::table('subscription_history')->where('payment_status', 'pending')->count()
            : 0;
        $outstandingInvoicesAmount = Schema::hasTable('subscription_history')
            ? DB::table('subscription_history')->where('payment_status', 'pending')->sum('amount')
            : 0;
        $outstandingInvoicesAmountFormatted = $outstandingInvoicesAmount > 0 ? 'Rp ' . number_format($outstandingInvoicesAmount, 0, ',', '.') : '—';
        $revenueForecast = Subscription::where('status', 'active')->sum('price');
        $revenueForecastFormatted = $revenueForecast > 0 ? 'Rp ' . number_format($revenueForecast, 0, ',', '.') : '—';

        // Customer lists
        $instansiWithStatus = Instansi::select('id', 'nama_instansi', 'subdomain', 'status_langganan', 'created_at')
            ->latest()->take(10)->get();
        $overduePayments = DB::table('subscription_requests')->where('payment_status', 'pending')->latest()->take(10)->get();

        // Activities & notifications
        $recentSubscriptionLogs = DB::table('subscription_requests')->latest()->take(10)->get();
        $superAdminId = DB::table('users')->where('role', 'superadmin')->value('id');
        $recentNotifications = Schema::hasTable('notifications')
            ? DB::table('notifications')
                ->when($superAdminId, fn($q) => $q->where('user_id', $superAdminId))
                ->latest()
                ->take(10)
                ->get()
            : collect();

        // Get notifications for the current user
        $notifications = Notification::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $unreadCount = Notification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->count();

        // Get instansi-related metrics (superadmin manages companies, not individual employees)
        $totalActiveInstansi = Instansi::where('status_langganan', 'active')->count();
        $totalInactiveInstansi = Instansi::where('status_langganan', 'inactive')->count();
        $totalSuspendedInstansi = Instansi::where('status_langganan', 'suspended')->count();


        // Calculate growth percentages
        $currentMonthInstansi = Instansi::whereMonth('created_at', now()->month)->count();
        $lastMonthInstansi = Instansi::whereMonth('created_at', now()->subMonth()->month)->count();
        $instansiGrowth = $lastMonthInstansi > 0
            ? round((($currentMonthInstansi - $lastMonthInstansi) / $lastMonthInstansi) * 100, 1) . '%'
            : '0%';

        $currentMonthActive = Instansi::where('status_langganan', 'active')
            ->whereMonth('updated_at', now()->month)
            ->count();
        $lastMonthActive = Instansi::where('status_langganan', 'active')
            ->whereMonth('updated_at', now()->subMonth()->month)
            ->count();
        $activeInstansiGrowth = $lastMonthActive > 0
            ? round((($currentMonthActive - $lastMonthActive) / $lastMonthActive) * 100, 1) . '%'
            : '0%';

        // Calculate revenue growth
        $revenueGrowth = $lastMonthRevenue > 0
            ? round((($thisMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100, 1) . '%'
            : '0%';

        // Get most popular package
        $mostPopularPackageData = Subscription::select('package_id', DB::raw('count(*) as total'))
            ->where('status', 'active')
            ->groupBy('package_id')
            ->with('package')
            ->orderByDesc('total')
            ->first();

        $mostPopularPackage = $mostPopularPackageData ? $mostPopularPackageData->package->name : 'N/A';
        $mostPopularPackageCount = $mostPopularPackageData ? $mostPopularPackageData->total : 0;

        // Calculate average MRR per instansi
        $avgMrr = $totalActiveInstansi > 0
            ? Subscription::where('status', 'active')->avg('price')
            : 0;
        $avgMrrFormatted = $avgMrr > 0 ? 'Rp ' . number_format($avgMrr, 0, ',', '.') : '-';

        return view('superadmin.dashboard.index', [
            'period' => $period,
            'totalInstansi' => $totalInstansi,
            'totalPackages' => $totalPackages,
            'totalActiveInstansi' => $totalActiveInstansi,
            'totalInactiveInstansi' => $totalInactiveInstansi,
            'totalSuspendedInstansi' => $totalSuspendedInstansi,
            'newCompanies7d' => $newCompanies7d,
            'expiring30d' => $expiring30d,
            'thisMonthRevenueFormatted' => $thisMonthRevenueFormatted,
            'notifications' => $notifications,
            'unreadCount' => $unreadCount,
            'instansiGrowth' => $instansiGrowth,
            'activeInstansiGrowth' => $activeInstansiGrowth,
            'revenueGrowth' => $revenueGrowth,
            'mostPopularPackage' => $mostPopularPackage,
            'mostPopularPackageCount' => $mostPopularPackageCount,
            'avgMrrFormatted' => $avgMrrFormatted,
            'monthlyRevenue' => $monthlyRevenue,
            'packageDistribution' => $packageDistribution,
            'monthlyInstansiCounts' => $monthlyInstansiCounts,
            'recentInstansi' => $recentInstansi,
            'recentSubscriptions' => $recentSubscriptions
        ]);
    }

    public function analytics(Request $request)
    {
        $period = $request->get('period', '30d');
        $since = match ($period) {
            '7d' => now()->subDays(7),
            '90d' => now()->subDays(90),
            '1y' => now()->subYear(),
            default => now()->subDays(30),
        };

        // Package distribution among active subscriptions
        $packageDistribution = Subscription::select('package_id', DB::raw('count(*) as total'))
            ->where('status', 'active')
            ->groupBy('package_id')
            ->with('package')
            ->get();

        // Monthly subscription counts in selected period
        $monthlySubscriptionCounts = Subscription::select(
            DB::raw('DATE_FORMAT(created_at, "%Y-%m") as ym'),
            DB::raw('count(*) as total')
        )
            ->where('created_at', '>=', $since)
            ->groupBy('ym')
            ->orderBy('ym')
            ->get();

        // Monthly instansi creation counts for the last 6 months (for growth chart)
        $monthlyInstansiCounts = Instansi::select(
            DB::raw('DATE_FORMAT(created_at, "%Y-%m") as ym'),
            DB::raw('count(*) as total')
        )
            ->where('created_at', '>=', now()->subMonths(5)->startOfMonth())
            ->groupBy('ym')
            ->orderBy('ym')
            ->get();

        // Monthly revenue data for the last 12 months from subscription_requests
        $monthlyRevenue = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $revenue = DB::table('subscription_requests')
                ->where('payment_status', 'paid')
                ->whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->sum('amount');
            $monthlyRevenue[] = [
                'month' => $date->format('M Y'),
                'revenue' => (float) $revenue
            ];
        }


        return view('superadmin.analytics.index', compact(
            'packageDistribution',
            'monthlySubscriptionCounts'
        ));
    }

    public function financial(Request $request)
    {
        // Financial summaries
        $pendingPayments = DB::table('subscription_requests')->where('payment_status', 'pending')->count();
        $outstandingInvoicesAmount = DB::table('subscription_requests')->where('payment_status', 'pending')->sum('amount');
        $outstandingInvoicesAmountFormatted = $outstandingInvoicesAmount > 0 ? 'Rp ' . number_format($outstandingInvoicesAmount, 0, ',', '.') : '—';
        $revenueForecast = Subscription::where('status', 'active')->sum('price');
        $revenueForecastFormatted = $revenueForecast > 0 ? 'Rp ' . number_format($revenueForecast, 0, ',', '.') : '—';

        return view('superadmin.financial.index', compact(
            'pendingPayments',
            'outstandingInvoicesAmountFormatted',
            'revenueForecastFormatted'
        ));
    }

    public function systemHealth(Request $request)
    {
        return view('superadmin.system.health');
    }

    public function reportsActivities(Request $request)
    {
        $period = $request->get('period', '7d');
        $since = match ($period) {
            '1d' => now()->subDay(),
            '7d' => now()->subDays(7),
            '30d' => now()->subDays(30),
            '90d' => now()->subDays(90),
            default => now()->subDays(7),
        };

        // Collect all activities in PHP arrays instead of complex UNION queries
        $allActivities = [];

        // User Activities
        $userCreations = DB::table('users')
            ->selectRaw("
                'User Registration' as activity_type,
                'user' as entity_type,
                id as entity_id,
                name as description,
                created_at as activity_date,
                'success' as status
            ")
            ->where('created_at', '>=', $since)
            ->get();

        $userUpdates = DB::table('users')
            ->selectRaw("
                'User Profile Update' as activity_type,
                'user' as entity_type,
                id as entity_id,
                CONCAT('Updated profile for ', name) as description,
                updated_at as activity_date,
                'info' as status
            ")
            ->where('updated_at', '>=', $since)
            ->whereColumn('updated_at', '>', 'created_at')
            ->get();

        $allActivities = array_merge($allActivities, $userCreations->toArray(), $userUpdates->toArray());

        // Subscription Activities
        $subscriptionCreations = DB::table('subscriptions')
            ->leftJoin('instansis', 'subscriptions.instansi_id', '=', 'instansis.id')
            ->leftJoin('packages', 'subscriptions.package_id', '=', 'packages.id')
            ->selectRaw("
                CASE
                    WHEN subscriptions.status = 'active' THEN 'Subscription Activated'
                    WHEN subscriptions.status = 'inactive' THEN 'Subscription Deactivated'
                    WHEN subscriptions.status = 'expired' THEN 'Subscription Expired'
                    WHEN subscriptions.status = 'cancelled' THEN 'Subscription Cancelled'
                    ELSE 'Subscription Created'
                END as activity_type,
                'subscription' as entity_type,
                subscriptions.id as entity_id,
                CONCAT('Subscription for ', COALESCE(instansis.nama_instansi, 'Unknown Instansi'), ' - ', COALESCE(packages.name, 'Unknown Package')) as description,
                subscriptions.created_at as activity_date,
                subscriptions.status as status
            ")
            ->where('subscriptions.created_at', '>=', $since)
            ->get();

        $subscriptionUpdates = DB::table('subscriptions')
            ->leftJoin('instansis', 'subscriptions.instansi_id', '=', 'instansis.id')
            ->selectRaw("
                'Subscription Updated' as activity_type,
                'subscription' as entity_type,
                subscriptions.id as entity_id,
                CONCAT('Updated subscription for ', COALESCE(instansis.nama_instansi, 'Unknown Instansi')) as description,
                subscriptions.updated_at as activity_date,
                subscriptions.status as status
            ")
            ->where('subscriptions.updated_at', '>=', $since)
            ->whereColumn('subscriptions.updated_at', '>', 'subscriptions.created_at')
            ->get();

        $allActivities = array_merge($allActivities, $subscriptionCreations->toArray(), $subscriptionUpdates->toArray());

        // Payment Activities
        $paymentActivities = DB::table('subscription_requests')
            ->leftJoin('instansis', 'subscription_requests.instansi_id', '=', 'instansis.id')
            ->selectRaw("
                CASE
                    WHEN subscription_requests.payment_status = 'paid' THEN 'Payment Completed'
                    WHEN subscription_requests.payment_status = 'pending' THEN 'Payment Requested'
                    WHEN subscription_requests.payment_status = 'cancelled' THEN 'Payment Cancelled'
                    ELSE 'Payment Created'
                END as activity_type,
                'payment' as entity_type,
                subscription_requests.id as entity_id,
                CONCAT('Payment of Rp ', subscription_requests.amount, ' for ', COALESCE(instansis.nama_instansi, 'Unknown Instansi')) as description,
                subscription_requests.created_at as activity_date,
                subscription_requests.payment_status as status
            ")
            ->where('subscription_requests.created_at', '>=', $since)
            ->get();

        $allActivities = array_merge($allActivities, $paymentActivities->toArray());


        // Instansi Activities
        $instansiCreations = DB::table('instansis')
            ->selectRaw("
                'Instansi Created' as activity_type,
                'instansi' as entity_type,
                id as entity_id,
                CONCAT('New instansi: ', nama_instansi) as description,
                created_at as activity_date,
                CASE WHEN is_active = 1 THEN 'active' ELSE 'inactive' END as status
            ")
            ->where('created_at', '>=', $since)
            ->get();

        $instansiUpdates = DB::table('instansis')
            ->selectRaw("
                'Instansi Updated' as activity_type,
                'instansi' as entity_type,
                id as entity_id,
                CONCAT('Updated instansi: ', nama_instansi) as description,
                updated_at as activity_date,
                CASE WHEN is_active = 1 THEN 'active' ELSE 'inactive' END as status
            ")
            ->where('updated_at', '>=', $since)
            ->whereColumn('updated_at', '>', 'created_at')
            ->get();

        $allActivities = array_merge($allActivities, $instansiCreations->toArray(), $instansiUpdates->toArray());

        // Employee Activities
        $employeeCreations = DB::table('employees')
            ->leftJoin('instansis', 'employees.instansi_id', '=', 'instansis.id')
            ->leftJoin('users', 'employees.user_id', '=', 'users.id')
            ->selectRaw("
                'Employee Added' as activity_type,
                'employee' as entity_type,
                employees.id as entity_id,
                CONCAT('New employee: ', COALESCE(users.name, 'Unknown'), ' at ', COALESCE(instansis.nama_instansi, 'Unknown Instansi')) as description,
                employees.created_at as activity_date,
                employees.status as status
            ")
            ->where('employees.created_at', '>=', $since)
            ->get();

        $employeeUpdates = DB::table('employees')
            ->leftJoin('instansis', 'employees.instansi_id', '=', 'instansis.id')
            ->leftJoin('users', 'employees.user_id', '=', 'users.id')
            ->selectRaw("
                'Employee Updated' as activity_type,
                'employee' as entity_type,
                employees.id as entity_id,
                CONCAT('Updated employee: ', COALESCE(users.name, 'Unknown'), ' at ', COALESCE(instansis.nama_instansi, 'Unknown Instansi')) as description,
                employees.updated_at as activity_date,
                employees.status as status
            ")
            ->where('employees.updated_at', '>=', $since)
            ->whereColumn('employees.updated_at', '>', 'employees.created_at')
            ->get();

        $allActivities = array_merge($allActivities, $employeeCreations->toArray(), $employeeUpdates->toArray());

        // Sort activities by date and limit to 50
        usort($allActivities, function ($a, $b) {
            return strtotime($b->activity_date) - strtotime($a->activity_date);
        });
        $recentActivities = array_slice($allActivities, 0, 50);

        // Convert to collection for view compatibility
        $recentActivities = collect($recentActivities)->map(function ($activity) {
            $activity->activity_date = \Carbon\Carbon::parse($activity->activity_date);
            return $activity;
        });

        // Activity counts by type
        $activityCounts = [
            'users' => count($userCreations) + count($userUpdates),
            'subscriptions' => count($subscriptionCreations) + count($subscriptionUpdates),
            'payments' => count($paymentActivities),
            'instansis' => count($instansiCreations) + count($instansiUpdates),
            'employees' => count($employeeCreations) + count($employeeUpdates),
        ];

        $totalActivities = array_sum($activityCounts);

        // Activity trends (last 7 days)
        $activityTrends = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $dayActivities = 0;

            // Count activities for this day across all tables
            $dayActivities += DB::table('users')->whereDate('created_at', $date)->count();
            $dayActivities += DB::table('subscriptions')->whereDate('created_at', $date)->count();
            $dayActivities += DB::table('subscription_requests')->whereDate('created_at', $date)->count();
            $dayActivities += DB::table('instansis')->whereDate('created_at', $date)->count();
            $dayActivities += DB::table('employees')->whereDate('created_at', $date)->count();

            $activityTrends[] = [
                'date' => $date->format('M j'),
                'count' => $dayActivities
            ];
        }

        return view('superadmin.reports.activities', compact(
            'period',
            'totalActivities',
            'activityCounts',
            'recentActivities',
            'activityTrends'
        ));
    }

    public function settingsProfile(Request $request)
    {
        return view('superadmin.settings.profile', [
            'user' => $request->user(),
        ]);
    }

    public function updateSettingsProfile(Request $request)
    {
        $request->validate([
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|string|email|max:255|unique:users,email,' . $request->user()->id,
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = $request->user();
        $updated = false;

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            // Store new avatar
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $avatarPath;
            $updated = true;
        }

        // Handle profile information update
        if ($request->filled('name') || $request->filled('email')) {
            $user->fill($request->only(['name', 'email']));

            if ($user->isDirty('email')) {
                $user->email_verified_at = null;
            }
            $updated = true;
        }

        if ($updated) {
            $user->save();
            return back()->with('status', 'profile-updated');
        }

        return back()->withErrors(['general' => 'No changes were made.']);
    }

    public function settingsNotifications(Request $request)
    {
        return view('superadmin.settings.notifications', [
            'user' => $request->user(),
        ]);
    }

    public function validateCurrentPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string',
        ]);

        $user = $request->user();

        if (\Hash::check($request->password, $user->password)) {
            return response()->json(['valid' => true]);
        }

        return response()->json(['valid' => false]);
    }

    public function updateSettingsNotifications(Request $request)
    {
        // For now, just redirect back with success
        // In a real implementation, you'd save notification preferences
        return back()->with('status', 'notifications-updated');
    }
}
