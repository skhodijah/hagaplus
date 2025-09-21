<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\SuperAdmin\Instansi;
use App\Models\SuperAdmin\Package;
use App\Models\SuperAdmin\Subscription;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Get dashboard statistics
        $totalInstansi = Instansi::count();
        $totalPackages = Package::count();
        $activeSubscriptions = Subscription::where('status', 'active')->count();
        $totalSubscriptions = Subscription::count();

        // Get recent data
        $recentInstansi = Instansi::latest()->take(5)->get();
        $recentPackages = Package::latest()->take(5)->get();
        $recentSubscriptions = Subscription::with(['instansi', 'package'])
            ->latest()
            ->take(5)
            ->get();

        // Get subscription statistics
        $subscriptionStats = [
            'active' => Subscription::where('status', 'active')->count(),
            'inactive' => Subscription::where('status', 'inactive')->count(),
            'expired' => Subscription::where('status', 'expired')->count(),
        ];

        return view('superadmin.dashboard.index', compact(
            'totalInstansi',
            'totalPackages',
            'activeSubscriptions',
            'totalSubscriptions',
            'recentInstansi',
            'recentPackages',
            'recentSubscriptions',
            'subscriptionStats'
        ));
    }
}
