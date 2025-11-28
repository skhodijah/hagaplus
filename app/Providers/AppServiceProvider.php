<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register Blade components
        $this->registerBladeComponents();
        
        // Register View Composers
        \Illuminate\Support\Facades\View::composer(
            'components.employee-layout',
            \App\Http\View\Composers\EmployeeLayoutComposer::class
        );

        // Register Model Observers
        \App\Models\SuperAdmin\Instansi::observe(\App\Observers\InstansiObserver::class);

        // Register Blade directives for permission checking
        Blade::if('hasPermission', function ($permission) {
            $user = auth()->user();
            
            if (!$user) {
                return false;
            }

            // Superadmin has all permissions
            if ($user->systemRole && $user->systemRole->slug === 'superadmin') {
                return true;
            }

            // Instansi owner (system role id 2) has full access when not linked to an employee record
            if ($user->system_role_id === 2 && !$user->employee) {
                return true;
            }

            // Check if user has employee record with instansi role
            if (!$user->employee || !$user->employee->instansiRole) {
                return false;
            }

            return $user->employee->instansiRole->hasPermission($permission);
        });

        Blade::if('hasAnyPermission', function (...$permissions) {
            $user = auth()->user();
            
            if (!$user) {
                return false;
            }

            // Superadmin has all permissions
            if ($user->systemRole && $user->systemRole->slug === 'superadmin') {
                return true;
            }

            // Instansi owner (system role id 2) has full access when not linked to an employee record
            if ($user->system_role_id === 2 && !$user->employee) {
                return true;
            }

            // Check if user has employee record with instansi role
            if (!$user->employee || !$user->employee->instansiRole) {
                return false;
            }

            return $user->employee->instansiRole->hasAnyPermission($permissions);
        });

        Blade::if('hasAllPermissions', function (...$permissions) {
            $user = auth()->user();
            
            if (!$user) {
                return false;
            }

            // Superadmin has all permissions
            if ($user->systemRole && $user->systemRole->slug === 'superadmin') {
                return true;
            }

            // Instansi owner (system role id 2) has full access when not linked to an employee record
            if ($user->system_role_id === 2 && !$user->employee) {
                return true;
            }

            // Check if user has employee record with instansi role
            if (!$user->employee || !$user->employee->instansiRole) {
                return false;
            }

            return $user->employee->instansiRole->hasAllPermissions($permissions);
        });
    }

    /**
     * Register custom Blade components.
     */
    protected function registerBladeComponents(): void
    {
        Blade::component('navigation', 'components.navigation');
        Blade::component('hero', 'components.hero');
        Blade::component('features', 'components.features');
        Blade::component('pricing', 'components.pricing');
        Blade::component('cta', 'components.cta');
        Blade::component('footer', 'components.footer');
        Blade::component('feature-card', 'components.feature-card');
        Blade::component('pricing-card', 'components.pricing-card');
    }
}
