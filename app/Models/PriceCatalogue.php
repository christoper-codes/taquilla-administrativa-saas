<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PriceCatalogue extends Model
{
    use HasFactory;

    protected $fillable = [
        'stadium_id',
        'price',
        'description',
        'is_active'
    ];

    public function stadium()
    {
        return $this->belongsTo(Stadium::class);
    }

    public function priceTypes()
    {
        return $this->belongsToMany(PriceType::class, 'price_type_seat_catalogue', 'price_catalogue_id', 'price_type_id')
                ->withPivot('seat_catalogue_id', 'is_active')
                ->withTimestamps();
    }

    public function eventSeatCatalogPriceTypes()
    {
        return $this->hasMany(EventSeatCatalogPriceType::class);
    }
}
