<?php

namespace App\Models\SuperAdmin;

use App\Models\Core\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subscription extends BaseModel
{
    protected $table = 'subscriptions';

    protected $fillable = [
        'instansi_id',
        'package_id',
        'status',
        'start_date',
        'end_date',
        'price',
        'payment_status',
        'payment_method',
        'payment_date',
        'notes',
    ];

    protected $dates = [
        'start_date',
        'end_date',
        'payment_date',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
        'start_date' => 'date',
        'end_date' => 'date',
        'price' => 'decimal:2',
    ];

    public function instansi()
    {
        return $this->belongsTo(Instansi::class);
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }
}
