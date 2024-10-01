<?php

namespace App\Providers;

use App\Interfaces\GlobalImageRepositoryInterface;
use App\Interfaces\SeatCatalogueRepositoryInterface;
use App\Repositories\GlobalImageRepository;
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
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
