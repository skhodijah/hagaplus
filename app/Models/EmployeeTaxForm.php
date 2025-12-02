<?php

namespace App\Models;

use App\Models\Admin\Employee;
use App\Models\Core\User;
use Illuminate\Database\Eloquent\Model;

class EmployeeTaxForm extends Model
{
    protected $fillable = [
        'employee_id',
        'tax_year',
        'period_start',
        'period_end',
        'form_number',
        'data',
        'is_published',
        'created_by',
    ];

    protected $casts = [
        'data' => 'array',
        'is_published' => 'boolean',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
