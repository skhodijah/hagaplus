<?php

namespace App\Models\Admin;

use App\Models\Core\BaseModel;

class Setting extends BaseModel
{
    protected $table = 'settings';

    protected $fillable = [
        'instansi_id',
        'key',
        'value',
        'type',
    ];

    public function instansi()
    {
        return $this->belongsTo(\App\Models\SuperAdmin\Instansi::class);
    }
}
