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
