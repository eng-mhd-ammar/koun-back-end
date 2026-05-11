<?php

namespace Modules\DonationRequestItem\Providers\DonationRequestItem;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider;
use Modules\DonationRequest\Controllers\V1\DonationRequestItemController;

class DonationRequestItemRouteServiceProvider extends RouteServiceProvider
{
    public function boot(): void
    {
        $this->routes(function (): void {
            Route::middleware('api')
                ->controller(DonationRequestItemController::class)
                ->prefix('api/v1/donation-request-item')
                ->name('donation-request-item.')
                ->group(__DIR__ . '/../../Routes/V1/donation-request-item.php');
        });
    }
}
