<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeatType extends Model
{
    use HasFactory;

    protected $fillable = [
        'stadium_id',
        'name',
        'code',
        'description',
        'is_active',
    ];

    public function stadium()
    {
        return $this->belongsTo(Stadium::class);
    }

    public function seatCatalogues()
    {
        return $this->hasMany(SeatCatalogue::class);
    }

    /* public function seasonTickets()
    {
        return $this->hasMany(SeasonTicket::class);
    } */
}
