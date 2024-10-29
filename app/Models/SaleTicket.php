<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleTicket extends Model
{
    use HasFactory;

    protected $fillable = [
        'seller_user_id',
        'cash_register_id',
        'sale_ticket_status_id',
        'price_type_id',
        'amount_received',
        'total_amount',
        'total_returned',
        'is_transfer',
        'is_online',
    ];

    public function events()
    {
        return $this->belongsToMany(Event::class, 'event_sale_ticket', 'sale_ticket_id', 'event_id')
            ->withPivot('is_active')
            ->withTimestamps();
    }

    public function sellerUser()
    {
        return $this->belongsTo(User::class, 'seller_user_id');
    }

    public function cashRegister()
    {
        return $this->belongsTo(CashRegister::class);
    }

    public function saleTicketStatus()
    {
        return $this->belongsTo(SaleTicketStatus::class);
    }

    public function globalPaymentTypes()
    {
        return $this->belongsToMany(GlobalPaymentType::class, 'global_payment_type_sale_ticket', 'sale_ticket_id', 'global_payment_type_id')
            ->withPivot('global_card_payment_type_id', 'amount', 'is_active')
            ->withTimestamps();
    }

    public function EventSeatCatalogues()
    {
        return $this->hasMany(EventSeatCatalog::class);
    }

    public function priceType()
    {
        return $this->belongsTo(PriceType::class);
    }

    public function cashRegisterMovements()
    {
        return $this->hasOne(CashRegisterMovement::class);
    }

    public function eventSeatCatalogs()
    {
        return $this->belongsToMany(EventSeatCatalog::class, 'event_seat_catalog_sale_ticket', 'sale_ticket_id', 'event_seat_catalog_id')
            ->withPivot('user_id', 'promotion_id', 'agreement_promotion_id', 'is_active')
            ->withTimestamps();
    }
}
