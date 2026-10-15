<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'staff.active' => \App\Http\Middleware\EnsureStaffIsActive::class,
            'user.type'    => \App\Http\Middleware\EnsureUserType::class,
        ]);

        $middleware->redirectGuestsTo(fn (Request $r) =>
            $r->is('admin/*') ? route('admin.login') : route('staff.login'));

        $middleware->redirectUsersTo(fn (Request $r) =>
            $r->is('admin/*') ? route('admin.dashboard') : route('staff.dashboard'));
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->shouldRenderJsonWhen(fn (Request $request, Throwable $e) =>
            $request->is('api/*') || $request->expectsJson());
    })
    ->create();