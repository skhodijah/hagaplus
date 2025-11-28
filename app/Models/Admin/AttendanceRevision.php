<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Core\User;

class AttendanceRevision extends Model
{
    use HasFactory;

    protected $fillable = [
        'attendance_id',
        'user_id',
        'approval_request_id',
        'revision_type',
        'original_time',
        'revised_time',
        'reason',
        'proof_photo',
        'status',
        'reviewed_by',
        'review_notes',
        'reviewed_at',
    ];

    protected $casts = [
        'original_time' => 'datetime',
        'revised_time' => 'datetime',
        'reviewed_at' => 'datetime',
    ];

    public function attendance()
    {
        return $this->belongsTo(Attendance::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    /**
     * Get the approval request for this revision
     */
    public function approvalRequest()
    {
        return $this->morphOne(ApprovalRequest::class, 'approvable');
    }
}
