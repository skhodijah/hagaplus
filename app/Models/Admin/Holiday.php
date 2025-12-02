<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    protected $fillable = [
        'start_date',
        'end_date',
        'name',
        'description',
        'is_national_holiday',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_national_holiday' => 'boolean',
    ];
}
