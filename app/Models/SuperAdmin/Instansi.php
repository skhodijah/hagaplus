<?php

namespace App\Models\SuperAdmin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Instansi extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'instansis';
    
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nama_instansi',
        'subdomain',
        'email',
        'phone',
        'address',
        'logo',
        'status_langganan',
        'package_id',
        'subscription_start',
        'subscription_end',
        'is_active',
        'max_employees',
        'max_branches',
        'settings',
        'retention_policy',
        'archived_at',
        'archived_by',
    ];

    /**
     * Get the users for the instansi.
     */
    public function users(): HasMany
    {
        return $this->hasMany(\App\Models\Core\User::class);
    }

    /**
     * Get the karyawans for the instansi.
     */
    public function karyawans(): HasMany
    {
        return $this->hasMany(Karyawan::class);
    }

    /**
     * Get the departemens for the instansi.
     */
    public function departemens(): HasMany
    {
        return $this->hasMany(Departemen::class);
    }
    
    /**
     * Get the subscriptions for the instansi.
     */
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class, 'instansi_id');
    }

    /**
     * Get the package for the instansi.
     */
    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    // Add these new relationships
    public function packageChangeRequests()
    {
        return $this->hasMany(PackageChangeRequest::class);
    }

    public function subscriptionTransitions()
    {
        return $this->hasMany(SubscriptionTransition::class);
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }

    public function featureOverrides()
    {
        return $this->hasMany(InstansiFeatureOverride::class);
    }

    public function dataExports()
    {
        return $this->hasMany(DataExport::class);
    }

    public function backupLogs()
    {
        return $this->hasMany(BackupLog::class);
    }

    public function complianceLogs()
    {
        return $this->hasMany(ComplianceLog::class);
    }

    public function dataDeletionRequests()
    {
        return $this->hasMany(DataDeletionRequest::class);
    }

    public function subscriptionAddons()
    {
        return $this->belongsToMany(SubscriptionAddon::class, 'instansi_subscription_addons')
            ->withPivot(['price_override', 'active_from', 'active_until', 'is_active'])
            ->withTimestamps();
    }

    public function discountUsages()
    {
        return $this->hasMany(DiscountUsage::class);
    }

    // Add these new methods
    public function hasFeature(string $featureSlug): bool
    {
        // Check if instansi has override for this feature
        $override = $this->featureOverrides()
            ->whereHas('feature', function ($query) use ($featureSlug) {
                $query->where('slug', $featureSlug);
            })
            ->active()
            ->first();

        if ($override) {
            return $override->is_enabled;
        }

        // Check package features
        return $this->package
            ->features()
            ->where('slug', $featureSlug)
            ->wherePivot('is_enabled', true)
            ->exists();
    }

    public function getFeatureLimit(string $featureSlug, string $limitKey)
    {
        // Check instansi override first
        $override = $this->featureOverrides()
            ->whereHas('feature', function ($query) use ($featureSlug) {
                $query->where('slug', $featureSlug);
            })
            ->active()
            ->first();

        if ($override && isset($override->custom_limits[$limitKey])) {
            return $override->custom_limits[$limitKey];
        }

        // Get from package feature
        $packageFeature = $this->package
            ->features()
            ->where('slug', $featureSlug)
            ->first();

        return $packageFeature?->pivot->limits[$limitKey] ?? null;
    }

    public function isEligibleForUpgrade(): bool
    {
        // Check if there's no pending package change request
        return !$this->packageChangeRequests()
            ->whereIn('status', ['pending', 'approved'])
            ->exists();
    }

    public function getTotalMonthlyAddonsPrice(): float
    {
        return $this->subscriptionAddons()
            ->wherePivot('is_active', true)
            ->get()
            ->sum(function ($addon) {
                return $addon->pivot->price_override ?? $addon->price;
            });
    }

    // Add soft delete traits and update casts
    protected $casts = [
        'subscription_start' => 'datetime',
        'subscription_end' => 'datetime',
        'settings' => 'array',
        'retention_policy' => 'array',
        'archived_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];
}
