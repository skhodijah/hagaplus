<?php

namespace App\Models\SuperAdmin;

use App\Models\Core\BaseModel;

class SupportRequest extends BaseModel
{
    protected $table = 'support_requests';

    protected $fillable = [
        'instansi_id',
        'requested_by',
        'subject',
        'message',
        'status', // open, in_progress, resolved, closed
        'priority', // low, normal, high, urgent
        'admin_notes',
    ];

    public function instansi()
    {
        return $this->belongsTo(Instansi::class);
    }

    public function requester()
    {
        return $this->belongsTo(\App\Models\Core\User::class, 'requested_by');
    }
}