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
use App\Interfaces\CashRegisterMovementRepositoryInterface;
use App\Interfaces\CashRegisterMovementTypeRepositoryInterface;
use App\Interfaces\CyberSourceRepositoryInterface;
use App\Interfaces\UserRepositoryInterface;
use App\Interfaces\EventSeatCatalogPromotionRepositoryInterface;
use App\Interfaces\EventSeatCatalogueRepositoryInterface;
use App\Interfaces\EventTypeRepositoryInterface;
use App\Interfaces\InstallmentPaymentHistoryRepositoryInterface;
use App\Interfaces\InstitutionRepositoryInterface;
use App\Interfaces\PromotionRepositoryInterface;
use App\Interfaces\PromotionTypeRepositoryInterface;
use App\Interfaces\SaleDebtorRepositoryInterface;
use App\Interfaces\SaleTicketRepositoryInterface;
use App\Interfaces\SeasonTicketRepositoryInterface;
use App\Interfaces\SeatCatalogueStatusesRepositoryInterface;
use App\Interfaces\SerieRepositoryInterface;
use App\Interfaces\StadiumRepositoryInterface;
use App\Interfaces\EventSeatCatalogPriceTypeRepositoryInterface;
use App\Interfaces\OnlinePaymentTransactionRepositoryInterface;
use App\Interfaces\PlatformSettingRepositoryInterface;
use App\Interfaces\PriceCatalogRepositoryInterface;
use App\Interfaces\PriceTypeRepositoryInterface;
use App\Interfaces\SeasonTicketWalletAccountRepositoryInterface;
use App\Interfaces\UtilRepositoryInterface;
use App\Interfaces\WalletAccountRepositoryInterface;
use App\Interfaces\WalletAccountRoleRepositoryInterface;
use App\Interfaces\WalletAccountTypeRepositoryInterface;
use App\Interfaces\WalletAccountWalletAccountTypeRepositoryInterface;
use App\Interfaces\WalletCurrencyRepositoryInterface;
use App\Interfaces\WalletRechargeAmountRepositoryInterface;
use App\Interfaces\WalletTransactionRepositoryInterface;
use App\Interfaces\WalletTransactionTypeRepositoryInterface;
use App\Repositories\AgreementRepository;
use App\Repositories\EventRepository;
use App\Repositories\GlobalCardPaymentTypeRepository;
use App\Repositories\GlobalImageRepository;
use App\Repositories\GlobalPaymentTypeRepository;
use App\Repositories\GlobalSeasonRepository;
use App\Repositories\SeatCatalogueRepository;
use App\Repositories\TicketOfficeRepository;
use App\Repositories\CardPaymentDetailRepository;
use App\Repositories\CashRegisterMovementRepository;
use App\Repositories\CashRegisterMovementTypeRepository;
use App\Repositories\CyberSourceRepository;
use App\Repositories\EventSeatCatalogPromotionRepository;
use App\Repositories\EventSeatCatalogueRepository;
use App\Repositories\EventTypeRepository;
use App\Repositories\InstallmentPaymentHistoryRepository;
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
use App\Repositories\EventSeatCatalogPriceTypeRepository;
use App\Repositories\OnlinePaymentTransactionRepository;
use App\Repositories\PlatformSettingRepository;
use App\Repositories\PriceCatalogRepository;
use App\Repositories\PriceTypeRepository;
use Illuminate\Support\ServiceProvider;
use App\Repositories\SeasonTicketWalletAccountRepository;
use App\Repositories\UtilRepository;
use App\Repositories\WalletAccountRepository;
use App\Repositories\WalletAccountRoleRepository;
use App\Repositories\WalletAccountTypeRepository;
use App\Repositories\WalletAccountWalletAccountTypeRepository;
use App\Repositories\WalletCurrencyRepository;
use App\Repositories\WalletRechargeAmountRepository;
use App\Repositories\WalletTransactionRepository;
use App\Repositories\WalletTransactionTypeRepository;

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
        $this->app->bind(PriceTypeRepositoryInterface::class, PriceTypeRepository::class);
        $this->app->bind(PriceCatalogRepositoryInterface::class, PriceCatalogRepository::class);
        $this->app->bind(EventSeatCatalogPriceTypeRepositoryInterface::class, EventSeatCatalogPriceTypeRepository::class);
        $this->app->bind(InstallmentPaymentHistoryRepositoryInterface::class, InstallmentPaymentHistoryRepository::class);
        $this->app->bind(CashRegisterMovementRepositoryInterface::class, CashRegisterMovementRepository::class);
        $this->app->bind(CashRegisterMovementTypeRepositoryInterface::class, CashRegisterMovementTypeRepository::class);
        $this->app->bind(CyberSourceRepositoryInterface::class, CyberSourceRepository::class);
        $this->app->bind(OnlinePaymentTransactionRepositoryInterface::class, OnlinePaymentTransactionRepository::class);
        $this->app->bind(WalletCurrencyRepositoryInterface::class, WalletCurrencyRepository::class);
        $this->app->bind(WalletAccountTypeRepositoryInterface::class, WalletAccountTypeRepository::class);
        $this->app->bind(WalletAccountRepositoryInterface::class, WalletAccountRepository::class);
        $this->app->bind(SeasonTicketWalletAccountRepositoryInterface::class, SeasonTicketWalletAccountRepository::class);
        $this->app->bind(WalletAccountWalletAccountTypeRepositoryInterface::class, WalletAccountWalletAccountTypeRepository::class);
        $this->app->bind(WalletAccountRoleRepositoryInterface::class, WalletAccountRoleRepository::class);
        $this->app->bind(PlatformSettingRepositoryInterface::class, PlatformSettingRepository::class);
        $this->app->bind(UtilRepositoryInterface::class, UtilRepository::class);
        $this->app->bind(WalletTransactionTypeRepositoryInterface::class, WalletTransactionTypeRepository::class);
        $this->app->bind(WalletTransactionRepositoryInterface::class, WalletTransactionRepository::class);
        $this->app->bind(WalletRechargeAmountRepositoryInterface::class, WalletRechargeAmountRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
