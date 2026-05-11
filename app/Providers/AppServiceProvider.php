<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Auth\Providers\Auth\AuthServiceProvider;
use App\Custom\CustomPaginator;
use Modules\Core\Rules\DefaultValue;
use Illuminate\Support\Facades\Validator;
use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Address\Providers\Address\AddressServiceProvider;
use Modules\Donation\Providers\Donation\DonationServiceProvider;
use Modules\DonationRequest\Providers\DonationRequest\DonationRequestServiceProvider;
use Modules\Institution\Providers\Institution\InstitutionServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->alias(CustomPaginator::class, LengthAwarePaginator::class);

        $this->app->register(AuthServiceProvider::class);
        $this->app->register(InstitutionServiceProvider::class);
        $this->app->register(AddressServiceProvider::class);
        $this->app->register(DonationServiceProvider::class);
        $this->app->register(DonationRequestServiceProvider::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Validator::extendImplicit('default', fn ($attribute, $value, $parameters, $validator) => DefaultValue::applyToValidator($validator, $attribute, $parameters));
    }
}
