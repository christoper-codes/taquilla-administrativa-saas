<?php

namespace App\Providers;

use App\Interfaces\AgreementRepositoryInterface;
use App\Interfaces\CashRegisterRepositoryInterface;
use App\Interfaces\EventRepositoryInterface;
use App\Interfaces\GlobalCardPaymentTypeRepositoryInterface;
use App\Interfaces\GlobalImageRepositoryInterface;
use App\Interfaces\GlobalPaymentTypeRepositoryInterface;
use App\Interfaces\GlobalSeasonRepositoryInterface;
use App\Interfaces\SeatCatalogueRepositoryInterface;
use App\Interfaces\TicketOfficeRepositoryInterface;
use App\Repositories\CashRegisterRepository;
use App\Interfaces\CardPaymentDetailRepositoryInterface;
use App\Interfaces\UserRepositoryInterface;
use App\Interfaces\EventSeatCatalogPromotionRepositoryInterface;
use App\Interfaces\EventSeatCatalogueRepositoryInterface;
use App\Interfaces\EventTypeRepositoryInterface;
use App\Interfaces\InstitutionRepositoryInterface;
use App\Interfaces\PromotionRepositoryInterface;
use App\Interfaces\PromotionTypeRepositoryInterface;
use App\Interfaces\SaleDebtorRepositoryInterface;
use App\Interfaces\SaleTicketRepositoryInterface;
use App\Interfaces\SeasonTicketRepositoryInterface;
use App\Interfaces\SeatCatalogueStatusesRepositoryInterface;
use App\Interfaces\SerieRepositoryInterface;
use App\Interfaces\StadiumRepositoryInterface;
use App\Models\Agreement;
use App\Models\Institution;
use App\Models\PromotionType;
use App\Repositories\AgreementRepository;
use App\Repositories\EventRepository;
use App\Repositories\GlobalCardPaymentTypeRepository;
use App\Repositories\GlobalImageRepository;
use App\Repositories\GlobalPaymentTypeRepository;
use App\Repositories\GlobalSeasonRepository;
use App\Repositories\SeatCatalogueRepository;
use App\Repositories\TicketOfficeRepository;
use App\Repositories\CardPaymentDetailRepository;
use App\Repositories\EventSeatCatalogPromotionRepository;
use App\Repositories\EventSeatCatalogueRepository;
use App\Repositories\EventTypeRepository;
use App\Repositories\InstitutionRepository;
use App\Repositories\PromotionRepository;
use App\Repositories\PromotionTypeRepository;
use App\Repositories\SaleDebtorRepository;
use App\Repositories\SaleTicketRepository;
use App\Repositories\SeasonTicketRepository;
use App\Repositories\SeatCatalogueStatusesRepository;
use App\Repositories\SerieRepository;
use App\Repositories\StadiumRepository;
use App\Repositories\UserRepository;
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
        * | Register the repository services by team
        */

        $this->app->bind(GlobalImageRepositoryInterface::class, GlobalImageRepository::class);
        $this->app->bind(SeatCatalogueRepositoryInterface::class, SeatCatalogueRepository::class);
        $this->app->bind(EventRepositoryInterface::class, EventRepository::class);
        $this->app->bind(GlobalPaymentTypeRepositoryInterface::class, GlobalPaymentTypeRepository::class);
        $this->app->bind(GlobalCardPaymentTypeRepositoryInterface::class, GlobalCardPaymentTypeRepository::class);
        $this->app->bind(TicketOfficeRepositoryInterface::class, TicketOfficeRepository::class);
        $this->app->bind(CashRegisterRepositoryInterface::class, CashRegisterRepository::class);
        $this->app->bind(CardPaymentDetailRepositoryInterface::class, CardPaymentDetailRepository::class);
        $this->app->bind(SerieRepositoryInterface::class, SerieRepository::class);
        $this->app->bind(GlobalSeasonRepositoryInterface::class, GlobalSeasonRepository::class);
        $this->app->bind(EventTypeRepositoryInterface::class, EventTypeRepository::class);
        $this->app->bind(EventSeatCatalogueRepositoryInterface::class, EventSeatCatalogueRepository::class);
        $this->app->bind(SeatCatalogueStatusesRepositoryInterface::class, SeatCatalogueStatusesRepository::class);
        $this->app->bind(InstitutionRepositoryInterface::class, InstitutionRepository::class);
        $this->app->bind(StadiumRepositoryInterface::class, StadiumRepository::class);
        $this->app->bind(AgreementRepositoryInterface::class, AgreementRepository::class);
        $this->app->bind(PromotionTypeRepositoryInterface::class, PromotionTypeRepository::class);
        $this->app->bind(PromotionRepositoryInterface::class, PromotionRepository::class);
        $this->app->bind(EventSeatCatalogPromotionRepositoryInterface::class, EventSeatCatalogPromotionRepository::class);
        $this->app->bind(SaleTicketRepositoryInterface::class, SaleTicketRepository::class);
        $this->app->bind(SeasonTicketRepositoryInterface::class, SeasonTicketRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(SaleDebtorRepositoryInterface::class, SaleDebtorRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
