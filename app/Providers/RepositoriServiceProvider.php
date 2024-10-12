<?php

namespace App\Providers;

use App\Interfaces\EventRepositoryInterface;
use App\Interfaces\GlobalCardPaymentTypeRepositoryInterface;
use App\Interfaces\GlobalImageRepositoryInterface;
use App\Interfaces\GlobalPaymentTypeRepositoryInterface;
use App\Interfaces\SeatCatalogueRepositoryInterface;
use App\Repositories\EventRepository;
use App\Repositories\GlobalCardPaymentTypeRepository;
use App\Repositories\GlobalImageRepository;
use App\Repositories\GlobalPaymentTypeRepository;
use App\Repositories\SeatCatalogueRepository;
use Illuminate\Support\ServiceProvider;

class RepositoriServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
         /*
        * |--------------------------------------------------------------------------
        * | Resgister services with bind method for the repository service provider
        * |--------------------------------------------------------------------------
        * | Register the repository services by Christoper Patiño
        */

        $this->app->bind(GlobalImageRepositoryInterface::class, GlobalImageRepository::class);
        $this->app->bind(SeatCatalogueRepositoryInterface::class, SeatCatalogueRepository::class);
        $this->app->bind(EventRepositoryInterface::class, EventRepository::class);
        $this->app->bind(GlobalPaymentTypeRepositoryInterface::class, GlobalPaymentTypeRepository::class);
        $this->app->bind(GlobalCardPaymentTypeRepositoryInterface::class, GlobalCardPaymentTypeRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
