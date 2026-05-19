<?php


use App\Http\Exceptions\ExceptionsHandler;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Modules\Donation\Middlewares\DonationOwner;
use Modules\DonationRequest\Middlewares\BranchOwner;
use Modules\Institution\Middlewares\EnsureAdminAccess;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'ensure_institution_admin' => EnsureAdminAccess::class,
            'donation_owner' => DonationOwner::class,
            'branch_owner' => BranchOwner::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        if (!env('APP_DEBUG')) {
            $exceptions->render(function (Throwable|Exception $exception) {
                if (request()->is('api/*')) {
                    return (new ExceptionsHandler())($exception);
                }
            });
        }
    })->create();
