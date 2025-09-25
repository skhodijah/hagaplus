<?php

namespace App\Models\SuperAdmin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComplianceLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'instansi_id',
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

    public function instansi()
    {
        return $this->belongsTo(Instansi::class);
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\Core\User::class);
    }

    public function scopeByInstansi($query, $instansiId)
    {
        return $query->where('instansi_id', $instansiId);
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