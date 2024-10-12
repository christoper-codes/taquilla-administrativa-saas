<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PriceTypeSeatCatalogue extends Model
{
    use HasFactory;

    protected $table = 'price_type_seat_catalogue';

    protected $fillable = [
        'seat_catalogue_id',
        'price_type_id',
        'price_catalogue_id',
        'is_active'
    ];

    public function seatCatalogue()
    {
        return $this->belongsTo(SeatCatalogue::class);
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
