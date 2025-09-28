<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Core\User;

class BulkNotificationLog extends Model
{
    protected $table = 'bulk_notification_logs';

    protected $fillable = [
        'title',
        'message',
        'type',
        'target_type',
        'target_ids',
        'total_sent',
        'sent_by',
    ];

    protected $casts = [
        'target_ids' => 'array',
    ];

    /**
     * Get the user who sent the bulk notification
     */
    public function sender()
    {
        return $this->belongsTo(User::class, 'sent_by');
    }

    /**
     * Get formatted target type
     */
    public function getFormattedTargetTypeAttribute()
    {
        return match ($this->target_type) {
            'all_admins' => 'All Admins',
            'specific_admins' => 'Specific Admins',
            'all_employees' => 'All Employees',
            'specific_employees' => 'Specific Employees',
            default => ucfirst(str_replace('_', ' ', $this->target_type))
        };
    }

    /**
     * Get type badge class
     */
    public function getTypeBadgeClassAttribute()
    {
        return match ($this->type) {
            'success' => 'bg-green-100 text-green-800',
            'error' => 'bg-red-100 text-red-800',
            'warning' => 'bg-yellow-100 text-yellow-800',
            'info' => 'bg-blue-100 text-blue-800',
            'system' => 'bg-purple-100 text-purple-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    /**
     * Get icon for notification type
     */
    public function getIconAttribute()
    {
        return match ($this->type) {
            'success' => 'check-circle',
            'error' => 'times-circle',
            'warning' => 'exclamation-triangle',
            'info' => 'info-circle',
            'system' => 'cog',
            default => 'bell'
        };
    }
}
