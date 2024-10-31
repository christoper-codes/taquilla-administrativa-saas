<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PriceType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'is_active',
    ];

    public function agreements()
    {
        return $this->hasMany(Agreement::class);
    }

    /* public function seatCatalogues()
    {
        return $this->belongsToMany(SeatCatalogue::class, 'price_type_seat_catalogue', 'price_type_id', 'seat_catalogue_id')
                ->withPivot('price_catalogue_id', 'is_active')
                ->withTimestamps();
    } */

    public function saleTickets()
    {
        return $this->hasMany(SaleTicket::class);
    }

    public function eventSeatCatalogs()
    {
        return $this->belongsToMany(EventSeatCatalog::class, 'event_seat_catalog_price_type', 'price_type_id', 'event_seat_catalog_id')
                ->withPivot('price_catalogue_id', 'price', 'is_active')
                ->withTimestamps();
    }

}
