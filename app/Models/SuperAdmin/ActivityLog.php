<?php

namespace App\Models\SuperAdmin;

use Spatie\Activitylog\Models\Activity;

class ActivityLog extends Activity
{
    protected $table = 'activity_logs';

    protected $casts = [
        'properties' => 'collection',
    ];

    public function instansi()
    {
        return $this->belongsTo(Instansi::class);
    }

    public function scopeByInstansi($query, $instansiId)
    {
        return $query->where('instansi_id', $instansiId);
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