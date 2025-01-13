<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeatCatalogue extends Model
{
    use HasFactory;

    protected $fillable = [
        'stadium_id',
        'zone_type_id',
        'seat_type_id',
        'row_type_id',
        'zone',
        'row',
        'seat',
        'code',
        'description',
        'is_active'
    ];

    public function stadium()
    {
        return $this->belongsTo(Stadium::class);
    }

    public function zoneType()
    {
        return $this->belongsTo(ZoneType::class);
    }

    public function seatType()
    {
        return $this->belongsTo(SeatType::class);
    }

    public function rowType()
    {
        return $this->belongsTo(RowType::class);
    }

    public function events()
    {
        return $this->belongsToMany(Event::class, 'event_seat_catalog', 'seat_catalogue_id', 'event_id')
                    ->withPivot('user_id','season_ticket_id', 'seat_catalogue_status_id', 'sale_ticket_id', 'qr', 'price', 'purchase_type', 'is_verified', 'is_active')
                    ->withTimestamps();
    }

    public function seasonTickets()
    {
        return $this->hasMany(SeasonTicket::class);
    }

    public function EventSeatCatalogues()
    {
        return $this->hasMany(EventSeatCatalog::class);
    }

}
