<?php

namespace Modules\DonationRequest\Providers\Delivery;

use Illuminate\Support\ServiceProvider;
use Modules\DonationRequest\Interfaces\V1\Delivery\DeliveryRepositoryInterface;
use Modules\DonationRequest\Interfaces\V1\Delivery\DeliveryServiceInterface;
use Modules\DonationRequest\Repositories\V1\DeliveryRepository;
use Modules\DonationRequest\Services\V1\DeliveryService;

class DeliveryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->register(DeliveryRouteServiceProvider::class);

        $this->app->bind(DeliveryServiceInterface::class, DeliveryService::class);
        $this->app->bind(DeliveryRepositoryInterface::class, DeliveryRepository::class);
    }

    public function boot(): void
    {
        //
    }
}
