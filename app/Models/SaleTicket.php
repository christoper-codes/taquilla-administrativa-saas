<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleTicket extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'seller_user_id',
        'cash_register_id',
        'sale_ticket_status_id',
        'price_type_id',
        'amount_received',
        'total_amount',
        'total_returned',
        'is_online',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
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
}
