<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Console\Scheduling\Schedule;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => \App\Http\Middleware\CheckRole::class,
            'subscription' => \App\Http\Middleware\CheckSubscription::class,
            'branch.access' => \App\Http\Middleware\CheckBranchAccess::class,
            'permission' => \App\Http\Middleware\CheckPermission::class,
        ]);
    })
    ->withSchedule(function (Schedule $schedule): void {
        // Send expiration warnings daily at 9 AM
        $schedule->command('app:send-expiration-warnings')
            ->dailyAt('09:00')
            ->withoutOverlapping()
            ->runInBackground();
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
