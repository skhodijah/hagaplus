<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class BaseModel extends Model
{
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the table name for the model.
     *
     * @return string
     */
    public function getTable()
    {
        return $this->table ?? str_replace('\\', '', Str::snake(class_basename($this)));
    }
}
