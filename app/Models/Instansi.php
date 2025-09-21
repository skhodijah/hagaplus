<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Instansi extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'instansis';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama_instansi',
        'subdomain',
        'status_langganan',
    ];

    /**
     * Get the users for the instansi.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get the karyawans for the instansi.
     */
    public function karyawans(): HasMany
    {
        return $this->hasMany(Karyawan::class);
    }

    /**
     * Get the departemens for the instansi.
     */
    public function departemens(): HasMany
    {
        return $this->hasMany(Departemen::class);
    }
}
