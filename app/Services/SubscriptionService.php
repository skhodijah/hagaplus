<?php

namespace App\Services;

use App\Models\SuperAdmin\Instansi;
use App\Models\SuperAdmin\Subscription;
use App\Models\Admin\Employee;
use App\Models\Core\User;
use App\Models\Admin\Branch;
use Carbon\Carbon;

class SubscriptionService
{
    protected $instansi;

    public function __construct(Instansi $instansi = null)
    {
        $this->instansi = $instansi;
    }

    public function setInstansi(Instansi $instansi)
    {
        $this->instansi = $instansi;
        return $this;
    }

    public function getActiveSubscription()
    {
        if (!$this->instansi) return null;

        return $this->instansi->subscriptions()
            ->where('status', 'active')
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->latest()
            ->first();
    }

    public function getSubscriptionStatus()
    {
        if (!$this->instansi) return 'no_instansi';

        $subscription = $this->instansi->subscriptions()
            ->latest('end_date')
            ->first();

        if (!$subscription) {
            return 'no_subscription';
        }

        if ($subscription->status === 'suspended') {
            return 'suspended';
        }

        if ($subscription->status === 'canceled') {
            return 'canceled';
        }

        if (now()->gt($subscription->end_date)) {
            return 'expired';
        }

        if (now()->diffInDays($subscription->end_date, false) <= 3) {
            return 'expiring_soon';
        }

        return 'active';
    }

    public function getRemainingDays()
    {
        $subscription = $this->getActiveSubscription();
        if (!$subscription) return 0;

        return now()->diffInDays($subscription->end_date, false);
    }

    public function canAddEmployee()
    {
        $subscription = $this->getActiveSubscription();
        if (!$subscription) return false;

        $limit = $subscription->package->max_employees;
        if (is_null($limit)) return true; // Unlimited

        $currentCount = Employee::where('instansi_id', $this->instansi->id)->count();
        return $currentCount < $limit;
    }

    public function canAddAdmin()
    {
        $subscription = $this->getActiveSubscription();
        if (!$subscription) return false;

        $limit = $subscription->package->max_admins;
        if (is_null($limit)) return true; // Unlimited

        // Count users with admin role (system_role_id = 2) in this instansi
        // Assuming system_role_id 2 is Admin/Instansi Admin
        $currentCount = User::where('instansi_id', $this->instansi->id)
            ->where('system_role_id', 2)
            ->count();
            
        return $currentCount < $limit;
    }

    public function canAddBranch()
    {
        $subscription = $this->getActiveSubscription();
        if (!$subscription) return false;

        $limit = $subscription->package->max_branches;
        if (is_null($limit)) return true; // Unlimited

        $currentCount = Branch::where('company_id', $this->instansi->id)->count();
        return $currentCount < $limit;
    }
    
    public function getLimits()
    {
        $subscription = $this->getActiveSubscription();
        if (!$subscription) return null;
        
        return [
            'max_employees' => $subscription->package->max_employees,
            'max_admins' => $subscription->package->max_admins,
            'max_branches' => $subscription->package->max_branches,
            'current_employees' => Employee::where('instansi_id', $this->instansi->id)->count(),
            'current_admins' => User::where('instansi_id', $this->instansi->id)->where('system_role_id', 2)->count(),
            'current_branches' => Branch::where('company_id', $this->instansi->id)->count(),
        ];
    }
}
