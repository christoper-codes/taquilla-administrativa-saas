<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventSeatCatalog extends Model
{
    use HasFactory;

    /*
    * |-----------------------------------------
    * | Table pivot | events | event_seat_catalogue | seat_catalogue_statuses
    */
    protected $table = 'event_seat_catalog';

    protected $fillable = [
        'event_id',
        'seat_catalogue_id',
        'user_id',
        'season_ticket_id',
        'seat_catalogue_status_id',
        'qr',
        'sale_ticket_id',
        'price',
        'original_price',
        'purchase_type',
        'is_gift',
        'is_verified',
        'is_active'
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function seatCatalogue()
    {
        return $this->belongsTo(SeatCatalogue::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function seasonTicket()
    {
        return $this->belongsTo(SeasonTicket::class);
    }

    public function seatCatalogueStatus()
    {
        return $this->belongsTo(SeatCatalogueStatus::class);
    }

    public function saleTicket()
    {
        return $this->belongsTo(SaleTicket::class);
    }

    public function priceTypes()
    {
        return $this->belongsToMany(PriceType::class, 'event_seat_catalog_price_type', 'event_seat_catalog_id', 'price_type_id')
                ->withPivot('price_catalogue_id', 'price', 'is_active')
                ->withTimestamps()
                ->wherePivot('event_seat_catalog_price_type.is_active', true);
    }

    public function promotions()
    {
        return $this->belongsToMany(Promotion::class, 'event_seat_catalog_promotion', 'event_seat_catalog_id', 'promotion_id')
                    ->withPivot('is_active')
                    ->withTimestamps()
                    ->wherePivot('event_seat_catalog_promotion.is_active', true);
    }

    public function saleTickets()
    {
        return $this->belongsToMany(SaleTicket::class, 'event_seat_catalog_sale_ticket', 'event_seat_catalog_id', 'sale_ticket_id')
                    ->withPivot('user_id', 'promotion_id', 'agreement_promotion_id', 'is_active')
                    ->withTimestamps();
    }


}
