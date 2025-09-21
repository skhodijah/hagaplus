<?php

namespace App\Models\SuperAdmin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Instansi extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'instansis';
    
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nama_instansi',
        'subdomain',
        'email',
        'phone',
        'alamat',
        'kota',
        'provinsi',
        'kode_pos',
        'negara',
        'website',
        'logo',
        'status',
        'status_langganan',
        'tanggal_mulai_langganan',
        'tanggal_akhir_langganan',
        'catatan',
    ];

    /**
     * Get the users for the instansi.
     */
    public function users(): HasMany
    {
        return $this->hasMany(\App\Models\Core\User::class);
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
    
    /**
     * Get the subscriptions for the instansi.
     */
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class, 'instansi_id');
    }
}
