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

    public function priceTypes()
    {
        return $this->belongsToMany(PriceType::class, 'price_type_seat_catalogue', 'seat_catalogue_id', 'price_type_id')
                ->withPivot('price_catalogue_id', 'is_active')
                ->withTimestamps();
    }

    public function events()
    {
        return $this->belongsToMany(Event::class, 'event_seat_catalogue', 'seat_catalogue_id', 'event_id')
                    ->withPivot('season_ticket_id', 'seat_catalogue_status_id', 'sale_ticket_id', 'is_verified')
                    ->withTimestamps();
    }
}
