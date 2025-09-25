<?php

namespace App\Models\SuperAdmin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BackupLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'instansi_id',
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

    public function instansi()
    {
        return $this->belongsTo(Instansi::class);
    }

    public function scopeByInstansi($query, $instansiId)
    {
        return $query->where('instansi_id', $instansiId);
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