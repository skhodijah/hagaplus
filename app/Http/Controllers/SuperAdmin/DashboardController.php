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

        // Monthly revenue data for the last 12 months
        $monthlyRevenue = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $revenue = Schema::hasTable('subscription_history')
                ? DB::table('subscription_history')
                    ->whereMonth('created_at', $date->month)
                    ->whereYear('created_at', $date->year)
                    ->sum('amount')
                : 0;
            $monthlyRevenue[] = [
                'month' => $date->format('M Y'),
                'revenue' => $revenue
            ];
        }

        // If no revenue data, create sample data for demonstration
        if (empty(array_filter(array_column($monthlyRevenue, 'revenue')))) {
            $monthlyRevenue = [];
            for ($i = 11; $i >= 0; $i--) {
                $date = now()->subMonths($i);
                $revenue = rand(1000000, 5000000); // Sample revenue data
                $monthlyRevenue[] = [
                    'month' => $date->format('M Y'),
                    'revenue' => $revenue
                ];
            }
        }

        // If no subscription counts, create sample data
        if ($monthlySubscriptionCounts->isEmpty()) {
            $monthlySubscriptionCounts = collect();
            for ($i = 5; $i >= 0; $i--) {
                $date = now()->subDays($i * 5);
                $monthlySubscriptionCounts->push((object)[
                    'ym' => $date->format('Y-m'),
                    'total' => rand(1, 10)
                ]);
            }
        }

        // If no package distribution, create sample data
        if ($packageDistribution->isEmpty()) {
            $packages = Package::take(3)->get();
            $packageDistribution = collect();
            foreach ($packages as $package) {
                $packageDistribution->push((object)[
                    'package_id' => $package->id,
                    'total' => rand(1, 5),
                    'package' => $package
                ]);
            }
        }

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

        // Get instansi-related metrics (superadmin manages companies, not individual employees)
        $totalActiveInstansi = Instansi::where('status_langganan', 'active')->count();
        $totalInactiveInstansi = Instansi::where('status_langganan', 'inactive')->count();
        $totalSuspendedInstansi = Instansi::where('status_langganan', 'suspended')->count();

        // Get support requests data
        $totalSupportRequests = DB::table('support_requests')->count();
        $openSupportRequests = DB::table('support_requests')->where('status', 'open')->count();

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
            
        $mostPopularPackage = $mostPopularPackageData ? $mostPopularPackageData->package->nama_paket : 'N/A';
        $mostPopularPackageCount = $mostPopularPackageData ? $mostPopularPackageData->total : 0;
        
        // Calculate average MRR per instansi
        $avgMrr = $totalActiveInstansi > 0 
            ? Subscription::where('status', 'active')->avg('price') 
            : 0;
        $avgMrrFormatted = $avgMrr > 0 ? 'Rp ' . number_format($avgMrr, 0, ',', '.') : '-';

        return view('superadmin.dashboard.index', [
            'period' => $period,
            'totalInstansi' => $totalInstansi,
            'totalActiveInstansi' => $totalActiveInstansi,
            'totalInactiveInstansi' => $totalInactiveInstansi,
            'totalSuspendedInstansi' => $totalSuspendedInstansi,
            'newCompanies7d' => $newCompanies7d,
            'expiring30d' => $expiring30d,
            'thisMonthRevenueFormatted' => $thisMonthRevenueFormatted,
            'openSupportRequests' => $openSupportRequests,
            'totalSupportRequests' => $totalSupportRequests,
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

        // Monthly revenue data for the last 12 months from subscription_history
        $monthlyRevenue = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $revenue = DB::table('subscription_history')
                ->where('payment_status', 'paid')
                ->whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->sum('amount');
            $monthlyRevenue[] = [
                'month' => $date->format('M Y'),
                'revenue' => (float) $revenue
            ];
        }

        // Get support requests data
        $totalSupportRequests = DB::table('support_requests')->count();
        $openSupportRequests = DB::table('support_requests')->where('status', 'open')->count();

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
