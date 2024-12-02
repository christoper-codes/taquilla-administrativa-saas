<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeasonTicket extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'seat_catalogue_id',
        'global_season_id',
        'serie_id',
        'holder_name',
        'holder_last_name',
        'holder_middle_name',
        'holder_email',
        'holder_phone',
        'holder_zip_code',
        'description',
        'is_owner',
        'is_active',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function seatCatalogue()
    {
        return $this->belongsTo(SeatCatalogue::class);
    }

    public function globalSeason()
    {
        return $this->belongsTo(GlobalSeason::class);
    }

    public function serie()
    {
        return $this->belongsTo(Serie::class);
    }

    public function EventSeatCatalogues()
    {
        return $this->hasMany(EventSeatCatalog::class);
    }
}
