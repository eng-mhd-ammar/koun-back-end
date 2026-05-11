<?php

namespace Modules\DonationRequest\Providers\DonationRequestItem;

use Illuminate\Support\ServiceProvider;
use Modules\DonationRequest\Interfaces\V1\DonationRequestItem\DonationRequestItemRepositoryInterface;
use Modules\DonationRequest\Interfaces\V1\DonationRequestItem\DonationRequestItemServiceInterface;
use Modules\DonationRequest\Repositories\V1\DonationRequestItemRepository;
use Modules\DonationRequest\Services\V1\DonationRequestItemService;
use Modules\DonationRequestItem\Providers\DonationRequestItem\DonationRequestItemRouteServiceProvider;

class DonationRequestItemServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->register(DonationRequestItemRouteServiceProvider::class);

        $this->app->bind(DonationRequestItemServiceInterface::class, DonationRequestItemService::class);
        $this->app->bind(DonationRequestItemRepositoryInterface::class, DonationRequestItemRepository::class);
    }

    public function boot(): void
    {
        //
    }
}
