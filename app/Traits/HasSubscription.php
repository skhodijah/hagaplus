<?php

namespace App\Traits;

trait HasSubscription
{
    /**
     * Check if user has active subscription
     */
    public function hasActiveSubscription()
    {
        if (!$this->instansi) {
            return false;
        }

        return $this->instansi->subscriptions()
            ->where('status', 'active')
            ->where('end_date', '>=', now())
            ->exists();
    }

    /**
     * Get active subscription
     */
    public function getActiveSubscription()
    {
        if (!$this->instansi) {
            return null;
        }

        return $this->instansi->subscriptions()
            ->where('status', 'active')
            ->where('end_date', '>=', now())
            ->first();
    }

    /**
     * Check if subscription is expired
     */
    public function isSubscriptionExpired()
    {
        if (!$this->instansi) {
            return true;
        }

        return $this->instansi->subscriptions()
            ->where('status', 'active')
            ->where('end_date', '<', now())
            ->exists();
    }
}
