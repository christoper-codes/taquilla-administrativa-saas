<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'stadium_id',
        'global_season_id',
        'event_type_id',
        'serie_id',
        'global_image_id',
        'event_visibility_type_id',
        'name',
        'slug',
        'description',
        'start_date',
        'end_date',
        'enabled_for_season_tickets',
        'is_active',
    ];

    public function stadium()
    {
        return $this->belongsTo(Stadium::class);
    }

    public function globalSeason()
    {
        return $this->belongsTo(GlobalSeason::class);
    }

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

    public function eventVisibilityType()
    {
        return $this->belongsTo(EventVisibilityType::class);
    }

    public function seatCatalogues()
    {
        return $this->belongsToMany(SeatCatalogue::class, 'event_seat_catalog', 'event_id', 'seat_catalogue_id')
                    ->withPivot('user_id', 'season_ticket_id', 'seat_catalogue_status_id', 'sale_ticket_id', 'qr', 'price', 'purchase_type', 'is_verified', 'is_active')
                    ->withTimestamps();
    }

    public function cashRegisters()
    {
        return $this->hasMany(CashRegister::class);
    }

    public function saleTickets()
    {
        return $this->belongsToMany(SaleTicket::class, 'event_sale_ticket', 'event_id', 'sale_ticket_id')
                    ->withPivot('is_active')
                    ->withTimestamps();
    }

    public function EventSeatCatalogues()
    {
        return $this->hasMany(EventSeatCatalog::class);
    }
}
