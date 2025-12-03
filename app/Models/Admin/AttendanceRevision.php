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
        'revision_type',
        'original_time',
        'revised_time',
        'reason',
        'proof_photo',
        'status',
        'reviewed_by',
        'review_notes',
        'reviewed_at',
        'supervisor_id',
        'supervisor_approved_at',
        'supervisor_note',
        'hrd_id',
        'hrd_approved_at',
        'hrd_note',
    ];

    protected $casts = [
        'original_time' => 'datetime',
        'revised_time' => 'datetime',
        'reviewed_at' => 'datetime',
        'supervisor_approved_at' => 'datetime',
        'hrd_approved_at' => 'datetime',
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
}
