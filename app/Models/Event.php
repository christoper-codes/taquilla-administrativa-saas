<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_type_id',
        'serie_id',
        'global_image_id',
        'name',
        'slug',
        'description',
        'start_date',
        'end_date',
        'is_active',
    ];

    public function eventType()
    {
        return $this->belongsTo(EventType::class);
    }

    public function serie()
    {
        return $this->belongsTo(Serie::class);
    }

    public function globalImage()
    {
        return $this->belongsTo(GlobalImage::class);
    }

    public function seatCatalogues()
    {
        return $this->belongsToMany(SeatCatalogue::class, 'event_seat_catalog', 'event_id', 'seat_catalogue_id')
                    ->withPivot('user_id', 'season_ticket_id', 'seat_catalogue_status_id', 'sale_ticket_id', 'qr', 'price', 'is_verified', 'is_active')
                    ->withTimestamps();
    }

    public function cashRegisters()
    {
        return $this->hasMany(CashRegister::class);
    }

    public function saleTickets()
    {
        return $this->hasMany(SaleTicket::class);
    }

    public function EventSeatCatalogues()
    {
        return $this->hasMany(EventSeatCatalog::class);
    }
}
