<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventSeatCatalogPriceType extends Model
{
    use HasFactory;

    protected $table = 'event_seat_catalog_price_type';

    protected $fillable = [
        'event_seat_catalog_id',
        'price_type_id',
        'price_catalogue_id',
        'price',
        'is_active',
    ];

    public function eventSeatCatalog()
    {
        return $this->belongsTo(EventSeatCatalog::class);
    }

    public function priceType()
    {
        return $this->belongsTo(PriceType::class);
    }

    public function priceCatalogue()
    {
        return $this->belongsTo(PriceCatalogue::class);
    }
}
