<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventSeatCatalogSaleTicket extends Model
{
    use HasFactory;

    protected $table = 'event_seat_catalog_sale_ticket';

    protected $fillable = [
        'event_seat_catalog_id',
        'sale_ticket_id',
        'user_id',
        'promotion_id',
        'agreement_promotion_id',
        'is_active'
    ];

    public function eventSeatCatalog()
    {
        return $this->belongsTo(EventSeatCatalog::class);
    }

    public function saleTicket()
    {
        return $this->belongsTo(SaleTicket::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function agreementPromotion()
    {
        return $this->belongsTo(AgreementPromotion::class);
    }
}
