<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class EventSeatCatalogPromotion extends Pivot
{
    use HasFactory;

    /*
    * |-----------------------------------------
    * | Table pivot | events | event_seat_catalogue | seat_catalogue_statuses
    */
    protected $table = 'event_seat_catalog_promotion';

    protected $fillable = [
        'event_seat_catalog_id',
        'promotion_id',
        'is_active',
    ];

    public function eventSeatCatalog()
    {
        return $this->belongsTo(EventSeatCatalog::class, 'event_seat_catalog_id');
    }

    public function promotion()
    {
        return $this->belongsTo(Promotion::class, 'promotion_id');
    }
}
