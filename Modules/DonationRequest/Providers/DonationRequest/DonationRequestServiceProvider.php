<?php

namespace Modules\DonationRequest\Providers\DonationRequest;

use Illuminate\Support\ServiceProvider;
use Modules\DonationRequest\Interfaces\V1\DonationRequest\DonationRequestRepositoryInterface;
use Modules\DonationRequest\Interfaces\V1\DonationRequest\DonationRequestServiceInterface;
use Modules\DonationRequest\Repositories\V1\DonationRequestRepository;
use Modules\DonationRequest\Services\V1\DonationRequestService;
use Modules\DonationRequest\Providers\Delivery\DeliveryServiceProvider;
use Modules\DonationRequest\Providers\DonationRequestItem\DonationRequestItemServiceProvider;

class DonationRequestServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->register(DonationRequestRouteServiceProvider::class);

        $this->app->bind(DonationRequestServiceInterface::class, DonationRequestService::class);
        $this->app->bind(DonationRequestRepositoryInterface::class, DonationRequestRepository::class);

        $this->app->register(DeliveryServiceProvider::class);
        $this->app->register(DonationRequestItemServiceProvider::class);
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . "/../../Database/migrations");
        $this->loadViewsFrom(__DIR__ . "/../../Views", 'donation-request');
    }
}
