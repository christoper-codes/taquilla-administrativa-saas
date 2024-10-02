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
        'sale_ticket_id',
        'price',
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


}
