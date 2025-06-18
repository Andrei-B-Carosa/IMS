<?php

use App\Http\Middleware\PreventAuthUser;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
            then: function () {
                Route::namespace('')
                    ->group(base_path('routes/employee.php'));

                Route::namespace('')
                    ->group(base_path('routes/select.php'));

                Route::namespace('')
                    ->group(base_path('routes/widget.php'));

            },
        )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->append(StartSession::class);
        $middleware->alias([
            'prevent.verified.user' => \App\Http\Middleware\PreventAuthUser::class,
        ]);
        $middleware->redirectGuestsTo(fn (Request $request) => route('employee.form.login'));
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
