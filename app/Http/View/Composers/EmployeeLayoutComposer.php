<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class EmployeeLayoutComposer
{
    /**
     * Bind data to the view.
     */
    public function compose(View $view): void
    {
        if (Auth::check() && Auth::user()->role === 'employee') {
            $employee = Auth::user()->employee;
            
            if ($employee && $employee->branch) {
                $view->with('branchData', [
                    'name' => $employee->branch->name ?? 'Kantor',
                    'latitude' => $employee->branch->latitude ?? null,
                    'longitude' => $employee->branch->longitude ?? null,
                    'radius' => $employee->branch->radius ?? 100,
                ]);
            } else {
                $view->with('branchData', [
                    'name' => 'Kantor',
                    'latitude' => null,
                    'longitude' => null,
                    'radius' => 100,
                ]);
            }
        }
    }
}
