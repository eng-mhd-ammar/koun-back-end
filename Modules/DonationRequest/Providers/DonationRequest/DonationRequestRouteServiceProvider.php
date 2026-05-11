<?php

namespace Modules\DonationRequest\Providers\DonationRequest;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider;
use Modules\DonationRequest\Controllers\V1\DonationRequestController;

class DonationRequestRouteServiceProvider extends RouteServiceProvider
{
    public function boot(): void
    {
        $this->routes(function (): void {
            Route::middleware('api')
                ->controller(DonationRequestController::class)
                ->prefix('api/v1/donation-request')
                ->name('donation-request.')
                ->group(__DIR__ . '/../../Routes/V1/donation-request.php');
        });
    }
}
