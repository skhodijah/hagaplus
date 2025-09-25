<?php

namespace App\Models\SuperAdmin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class DataExport extends Model
{
    use HasFactory;

    protected $fillable = [
        'instansi_id',
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

    public function instansi()
    {
        return $this->belongsTo(Instansi::class);
    }

    public function requestedBy()
    {
        return $this->belongsTo(\App\Models\Core\User::class, 'requested_by');
    }

    public function scopeByInstansi($query, $instansiId)
    {
        return $query->where('instansi_id', $instansiId);
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