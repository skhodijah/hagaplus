<?php

namespace App\Helpers;

class Helper
{
    /**
     * Format currency
     */
    public static function formatCurrency($amount, $currency = 'IDR')
    {
        return $currency . ' ' . number_format($amount, 0, ',', '.');
    }

    /**
     * Format date
     */
    public static function formatDate($date, $format = 'd/m/Y')
    {
        return $date ? $date->format($format) : '-';
    }

    /**
     * Format datetime
     */
    public static function formatDateTime($datetime, $format = 'd/m/Y H:i')
    {
        return $datetime ? $datetime->format($format) : '-';
    }

    /**
     * Generate random string
     */
    public static function generateRandomString($length = 10)
    {
        return substr(str_shuffle(str_repeat($x = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length / strlen($x)))), 1, $length);
    }

    /**
     * Calculate working hours
     */
    public static function calculateWorkingHours($checkIn, $checkOut)
    {
        if (!$checkIn || !$checkOut) {
            return 0;
        }

        return $checkIn->diffInHours($checkOut);
    }

    /**
     * Generate breadcrumbs based on current route
     */
    public static function generateBreadcrumbs()
    {
        $route = request()->route();
        if (!$route) {
            return [];
        }

        $routeName = $route->getName();
        $breadcrumbs = [];

        // Always start with Dashboard for superadmin
        if (str_starts_with($routeName, 'superadmin.')) {
            $breadcrumbs[] = [
                'title' => 'Dashboard',
                'url' => route('superadmin.dashboard')
            ];

            // Remove 'superadmin.' prefix
            $routeSuffix = str_replace('superadmin.', '', $routeName);

            switch ($routeSuffix) {
                case 'dashboard':
                    // Already added, remove url to make it current
                    $breadcrumbs[0]['url'] = null;
                    break;

                case 'analytics.index':
                    $breadcrumbs[] = [
                        'title' => 'Analytics Dashboard',
                        'url' => null
                    ];
                    break;

                case 'financial.index':
                    $breadcrumbs[] = [
                        'title' => 'Financial Overview',
                        'url' => null
                    ];
                    break;

                case 'system.health':
                    $breadcrumbs[] = [
                        'title' => 'System Health',
                        'url' => null
                    ];
                    break;

                case 'reports.activities':
                    $breadcrumbs[] = [
                        'title' => 'Recent Activities',
                        'url' => null
                    ];
                    break;

                case 'instansi.index':
                    $breadcrumbs[] = [
                        'title' => 'Instansi Management',
                        'url' => null
                    ];
                    break;

                case 'instansi.monitoring':
                    $breadcrumbs[] = [
                        'title' => 'Instansi Management',
                        'url' => route('superadmin.instansi.index')
                    ];
                    $breadcrumbs[] = [
                        'title' => 'Usage Monitoring',
                        'url' => null
                    ];
                    break;

                case 'instansi.create':
                    $breadcrumbs[] = [
                        'title' => 'Instansi Management',
                        'url' => route('superadmin.instansi.index')
                    ];
                    $breadcrumbs[] = [
                        'title' => 'Create Instansi',
                        'url' => null
                    ];
                    break;

                case 'instansi.show':
                    $breadcrumbs[] = [
                        'title' => 'Instansi Management',
                        'url' => route('superadmin.instansi.index')
                    ];
                    $breadcrumbs[] = [
                        'title' => 'View Instansi',
                        'url' => null
                    ];
                    break;

                case 'instansi.edit':
                    $breadcrumbs[] = [
                        'title' => 'Instansi Management',
                        'url' => route('superadmin.instansi.index')
                    ];
                    $breadcrumbs[] = [
                        'title' => 'Edit Instansi',
                        'url' => null
                    ];
                    break;

                case 'packages.index':
                    $breadcrumbs[] = [
                        'title' => 'Package Management',
                        'url' => null
                    ];
                    break;

                case 'subscriptions.index':
                    $breadcrumbs[] = [
                        'title' => 'Subscription Status',
                        'url' => null
                    ];
                    break;

                default:
                    // For unknown routes, just show the route name
                    $breadcrumbs[] = [
                        'title' => ucwords(str_replace(['.', '_'], ' ', $routeSuffix)),
                        'url' => null
                    ];
                    break;
            }
        }

        return $breadcrumbs;
    }
}
