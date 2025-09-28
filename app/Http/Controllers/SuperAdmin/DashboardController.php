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
        $overduePayments = Schema::hasTable('subscription_history')
            ? DB::table('subscription_history')->where('payment_status', 'pending')->latest()->take(10)->get()
            : collect();

        // Activities & notifications
        $recentSubscriptionLogs = Schema::hasTable('subscription_history')
            ? DB::table('subscription_history')->latest()->take(10)->get()
            : collect();
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

        return view('superadmin.dashboard.index', compact(
            'period',
            'totalActiveCompanies',
            'newCompanies7d',
            'expiring30d',
            'activeUsers',
            'thisMonthRevenueFormatted',
            'mrrGrowthPct',
            'totalInstansi',
            'totalPackages',
            'activeSubscriptions',
            'totalSubscriptions',
            'recentInstansi',
            'recentPackages',
            'recentSubscriptions',
            'subscriptionStats',
            'packageDistribution',
            'monthlySubscriptionCounts',
            'pendingPayments',
            'outstandingInvoicesAmountFormatted',
            'revenueForecastFormatted',
            'instansiWithStatus',
            'overduePayments',
            'recentSubscriptionLogs',
            'recentNotifications',
            'notifications',
            'unreadCount'
        ));
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

        return view('superadmin.analytics.index', compact(
            'packageDistribution',
            'monthlySubscriptionCounts'
        ));
    }

    public function financial(Request $request)
    {
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
        // Activities & notifications
        $recentSubscriptionLogs = Schema::hasTable('subscription_history')
            ? DB::table('subscription_history')->latest()->take(10)->get()
            : collect();

        return view('superadmin.reports.activities', compact(
            'recentSubscriptionLogs'
        ));
    }
}
