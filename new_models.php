<?php

// Model 1: PackageChangeRequest.php
// File: app/Models/PackageChangeRequest.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class PackageChangeRequest extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'company_id',
        'current_package_id',
        'requested_package_id',
        'type',
        'status',
        'requested_effective_date',
        'prorate_amount',
        'reason',
        'admin_notes',
        'requested_by',
        'approved_by',
        'approved_at'
    ];

    protected $casts = [
        'requested_effective_date' => 'date',
        'prorate_amount' => 'decimal:2',
        'approved_at' => 'datetime'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['status', 'approved_by', 'admin_notes'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function currentPackage()
    {
        return $this->belongsTo(Package::class, 'current_package_id');
    }

    public function requestedPackage()
    {
        return $this->belongsTo(Package::class, 'requested_package_id');
    }

    public function requestedBy()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function scopeByCompany($query, $companyId)
    {
        return $query->where('company_id', $companyId);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function isUpgrade(): bool
    {
        return $this->type === 'upgrade';
    }

    public function isDowngrade(): bool
    {
        return $this->type === 'downgrade';
    }
}

// Model 2: SubscriptionTransition.php
// File: app/Models/SubscriptionTransition.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionTransition extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'from_package_id',
        'to_package_id',
        'subscription_id',
        'transition_type',
        'effective_from',
        'effective_until',
        'transition_amount',
        'prorate_credit',
        'feature_changes',
        'notes',
        'processed_by'
    ];

    protected $casts = [
        'effective_from' => 'datetime',
        'effective_until' => 'datetime',
        'transition_amount' => 'decimal:2',
        'prorate_credit' => 'decimal:2',
        'feature_changes' => 'array'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function fromPackage()
    {
        return $this->belongsTo(Package::class, 'from_package_id');
    }

    public function toPackage()
    {
        return $this->belongsTo(Package::class, 'to_package_id');
    }

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    public function processedBy()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }
}

// Model 3: ActivityLog.php (Using Spatie Activity Log)
// File: app/Models/ActivityLog.php

namespace App\Models;

use Spatie\Activitylog\Models\Activity;

class ActivityLog extends Activity
{
    protected $table = 'activity_logs';

    protected $casts = [
        'properties' => 'collection',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function scopeByCompany($query, $companyId)
    {
        return $query->where('company_id', $companyId);
    }

    public function scopeByEvent($query, $event)
    {
        return $query->where('event', $event);
    }

    public function scopeBySubjectType($query, $type)
    {
        return $query->where('subject_type', $type);
    }
}

// Model 4: Feature.php
// File: app/Models/Feature.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feature extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'category',
        'is_active',
        'sort_order',
        'config'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'config' => 'array'
    ];

    public function packages()
    {
        return $this->belongsToMany(Package::class, 'package_features')
            ->withPivot(['is_enabled', 'limits', 'config_override'])
            ->withTimestamps();
    }

    public function companyOverrides()
    {
        return $this->hasMany(CompanyFeatureOverride::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }
}

// Model 5: PackageFeature.php
// File: app/Models/PackageFeature.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackageFeature extends Model
{
    use HasFactory;

    protected $fillable = [
        'package_id',
        'feature_id',
        'is_enabled',
        'limits',
        'config_override'
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
        'limits' => 'array',
        'config_override' => 'array'
    ];

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function feature()
    {
        return $this->belongsTo(Feature::class);
    }
}

// Model 6: CompanyFeatureOverride.php
// File: app/Models/CompanyFeatureOverride.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class CompanyFeatureOverride extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'company_id',
        'feature_id',
        'is_enabled',
        'custom_limits',
        'custom_config',
        'reason',
        'effective_from',
        'effective_until',
        'applied_by'
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
        'custom_limits' => 'array',
        'custom_config' => 'array',
        'effective_from' => 'date',
        'effective_until' => 'date'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['is_enabled', 'custom_limits', 'custom_config', 'reason'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function feature()
    {
        return $this->belongsTo(Feature::class);
    }

    public function appliedBy()
    {
        return $this->belongsTo(User::class, 'applied_by');
    }

    public function scopeActive($query)
    {
        $now = now()->toDateString();
        return $query->where('effective_from', '<=', $now)
            ->where(function ($q) use ($now) {
                $q->whereNull('effective_until')
                  ->orWhere('effective_until', '>=', $now);
            });
    }

    public function scopeByCompany($query, $companyId)
    {
        return $query->where('company_id', $companyId);
    }
}

// Model 7: DataExport.php
// File: app/Models/DataExport.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class DataExport extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'requested_by',
        'export_type',
        'format',
        'filters',
        'status',
        'file_path',
        'file_size',
        'expires_at',
        'error_message'
    ];

    protected $casts = [
        'filters' => 'array',
        'expires_at' => 'datetime'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function requestedBy()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function scopeByCompany($query, $companyId)
    {
        return $query->where('company_id', $companyId);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeNotExpired($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('expires_at')
              ->orWhere('expires_at', '>', now());
        });
    }

    public function getDownloadUrl()
    {
        if ($this->status === 'completed' && $this->file_path) {
            return Storage::url($this->file_path);
        }
        return null;
    }

    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    public function getFileSizeHuman(): string
    {
        if (!$this->file_size) {
            return 'Unknown';
        }

        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];
        $factor = floor((strlen($bytes) - 1) / 3);
        
        return sprintf("%.2f %s", $bytes / pow(1024, $factor), $units[$factor]);
    }
}

// Model 8: BackupLog.php
// File: app/Models/BackupLog.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BackupLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'backup_type',
        'status',
        'backup_path',
        'backup_size',
        'tables_included',
        'records_count',
        'started_at',
        'completed_at',
        'error_details',
        'initiated_by'
    ];

    protected $casts = [
        'tables_included' => 'array',
        'started_at' => 'datetime',
        'completed_at' => 'datetime'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function scopeByCompany($query, $companyId)
    {
        return $query->where('company_id', $companyId);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    public function getDuration()
    {
        if ($this->started_at && $this->completed_at) {
            return $this->started_at->diffForHumans($this->completed_at, true);
        }
        return null;
    }

    public function getBackupSizeHuman(): string
    {
        if (!$this->backup_size) {
            return 'Unknown';
        }

        $bytes = $this->backup_size;
        $units = ['B', 'KB', 'MB', 'GB'];
        $factor = floor((strlen($bytes) - 1) / 3);
        
        return sprintf("%.2f %s", $bytes / pow(1024, $factor), $units[$factor]);
    }
}

// Model 9: ComplianceLog.php
// File: app/Models/ComplianceLog.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComplianceLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'compliance_type',
        'event',
        'description',
        'affected_data',
        'user_id',
        'ip_address',
        'metadata'
    ];

    protected $casts = [
        'affected_data' => 'array',
        'metadata' => 'array'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeByCompany($query, $companyId)
    {
        return $query->where('company_id', $companyId);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('compliance_type', $type);
    }

    public function scopeByEvent($query, $event)
    {
        return $query->where('event', $event);
    }
}

// Model 10: DataDeletionRequest.php
// File: app/Models/DataDeletionRequest.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class DataDeletionRequest extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'company_id',
        'requested_by',
        'request_type',
        'data_specification',
        'reason',
        'status',
        'approved_by',
        'approved_at',
        'scheduled_deletion_date',
        'completed_at',
        'admin_notes'
    ];

    protected $casts = [
        'data_specification' => 'array',
        'approved_at' => 'datetime',
        'scheduled_deletion_date' => 'date',
        'completed_at' => 'datetime'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['status', 'approved_by', 'admin_notes', 'scheduled_deletion_date'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function requestedBy()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function scopeByCompany($query, $companyId)
    {
        return $query->where('company_id', $companyId);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeScheduledForToday($query)
    {
        return $query->where('scheduled_deletion_date', now()->toDateString())
            ->where('status', 'approved');
    }

    public function isDue(): bool
    {
        return $this->status === 'approved' && 
               $this->scheduled_deletion_date->isToday();
    }
}

// Model 11: SubscriptionAddon.php
// File: app/Models/SubscriptionAddon.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionAddon extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'billing_cycle',
        'features',
        'is_active'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'features' => 'array',
        'is_active' => 'boolean'
    ];

    public function companies()
    {
        return $this->belongsToMany(Company::class, 'company_subscription_addons')
            ->withPivot(['price_override', 'active_from', 'active_until', 'is_active'])
            ->withTimestamps();
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getFormattedPrice(): string
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }
}

// Model 12: CompanySubscriptionAddon.php
// File: app/Models/CompanySubscriptionAddon.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanySubscriptionAddon extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'subscription_addon_id',
        'price_override',
        'active_from',
        'active_until',
        'is_active'
    ];

    protected $casts = [
        'price_override' => 'decimal:2',
        'active_from' => 'date',
        'active_until' => 'date',
        'is_active' => 'boolean'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function addon()
    {
        return $this->belongsTo(SubscriptionAddon::class, 'subscription_addon_id');
    }

    public function scopeActive($query)
    {
        $now = now()->toDateString();
        return $query->where('is_active', true)
            ->where('active_from', '<=', $now)
            ->where(function ($q) use ($now) {
                $q->whereNull('active_until')
                  ->orWhere('active_until', '>=', $now);
            });
    }

    public function getEffectivePrice(): float
    {
        return $this->price_override ?? $this->addon->price;
    }
}

// Model 13: Discount.php
// File: app/Models/Discount.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'type',
        'value',
        'max_discount',
        'usage_limit',
        'usage_limit_per_company',
        'used_count',
        'valid_from',
        'valid_until',
        'applicable_packages',
        'target',
        'is_active'
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'max_discount' => 'decimal:2',
        'valid_from' => 'date',
        'valid_until' => 'date',
        'applicable_packages' => 'array',
        'is_active' => 'boolean'
    ];

    public function usages()
    {
        return $this->hasMany(DiscountUsage::class);
    }

    public function companies()
    {
        return $this->belongsToMany(Company::class, 'discount_usage')
            ->withPivot(['original_amount', 'discount_amount', 'final_amount'])
            ->withTimestamps();
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeValid($query)
    {
        $now = now()->toDateString();
        return $query->where('valid_from', '<=', $now)
            ->where('valid_until', '>=', $now);
    }

    public function scopeByCode($query, $code)
    {
        return $query->where('code', $code);
    }

    public function isValid(): bool
    {
        $now = now()->toDateString();
        return $this->is_active &&
               $this->valid_from <= $now &&
               $this->valid_until >= $now &&
               ($this->usage_limit === null || $this->used_count < $this->usage_limit);
    }

    public function calculateDiscount(float $amount): float
    {
        if ($this->type === 'percentage') {
            $discount = ($amount * $this->value) / 100;
            return $this->max_discount ? min($discount, $this->max_discount) : $discount;
        }
        
        return min($this->value, $amount);
    }

    public function canBeUsedByCompany(Company $company): bool
    {
        if (!$this->isValid()) {
            return false;
        }

        $companyUsageCount = $this->usages()
            ->where('company_id', $company->id)
            ->count();

        return $companyUsageCount < $this->usage_limit_per_company;
    }

    public function getFormattedValue(): string
    {
        return $this->type === 'percentage' 
            ? $this->value . '%'
            : 'Rp ' . number_format($this->value, 0, ',', '.');
    }
}

// Model 14: DiscountUsage.php
// File: app/Models/DiscountUsage.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscountUsage extends Model
{
    use HasFactory;

    protected $table = 'discount_usage';

    protected $fillable = [
        'discount_id',
        'company_id',
        'subscription_id',
        'original_amount',
        'discount_amount',
        'final_amount'
    ];

    protected $casts = [
        'original_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'final_amount' => 'decimal:2'
    ];

    public function discount()
    {
        return $this->belongsTo(Discount::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    public function getSavingsPercentage(): float
    {
        if ($this->original_amount == 0) {
            return 0;
        }

        return ($this->discount_amount / $this->original_amount) * 100;
    }
}

// Updated existing models to include new relationships and methods

// Update Company.php model
// Add these methods to existing Company model:

/*
class Company extends Model
{
    // ... existing code ...

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
        return $this->hasMany(CompanyFeatureOverride::class);
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
        return $this->belongsToMany(SubscriptionAddon::class, 'company_subscription_addons')
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
        // Check if company has override for this feature
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
        // Check company override first
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
        // ... existing casts ...
        'retention_policy' => 'array',
        'archived_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    // Add this to fillable
    protected $fillable = [
        // ... existing fillable ...
        'retention_policy',
        'archived_at',
        'archived_by',
    ];
}
*/