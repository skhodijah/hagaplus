<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PayrollComponent extends Model
{
    protected $fillable = [
        'instansi_id',
        'name',
        'type',
        'is_taxable',
        'is_default',
        'default_amount',
    ];

    protected $casts = [
        'is_taxable' => 'boolean',
        'is_default' => 'boolean',
        'default_amount' => 'decimal:2',
    ];

    public function instansi(): BelongsTo
    {
        return $this->belongsTo(Instansi::class);
    }
}
