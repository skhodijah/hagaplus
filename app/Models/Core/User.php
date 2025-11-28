<?php

namespace App\Models\Core;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use App\Traits\HasRole;
use App\Traits\HasSubscription;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRole, HasSubscription;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'system_role_id',
        'instansi_id',
        'avatar',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        if (!$this->name) {
            return 'U'; // Default initial for users without names
        }

        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    /**
     * Get the instansi that owns the user
     */
    public function instansi()
    {
        return $this->belongsTo(\App\Models\SuperAdmin\Instansi::class);
    }

    /**
     * Get the system role of the user
     */
    public function systemRole()
    {
        return $this->belongsTo(\App\Models\Admin\SystemRole::class, 'system_role_id');
    }

    /**
     * Get the employee record for the user
     */
    public function employee()
    {
        return $this->hasOne(\App\Models\Admin\Employee::class);
    }

    /**
     * Get the attendances for the user
     */
    public function attendances()
    {
        return $this->hasMany(\App\Models\Admin\Attendance::class);
    }

    /**
     * Get the payrolls for the user
     */
    public function payrolls()
    {
        return $this->hasMany(\App\Models\Admin\Payroll::class);
    }

    /**
     * Get the employee policy for the user
     */
    public function employeePolicy()
    {
        return $this->hasOne(\App\Models\EmployeePolicy::class, 'employee_id');
    }

    /**
     * Send the password reset notification.
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new \App\Notifications\ResetPasswordNotification($token));
    }
}
