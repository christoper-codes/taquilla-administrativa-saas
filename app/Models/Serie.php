<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Serie extends Model
{
    use HasFactory;

    protected $fillable = [
        'global_season_id',
        'name',
        'description',
        'start_date',
        'end_date',
        'is_active'
    ];

    public function globalSeason()
    {
        return $this->belongsTo(GlobalSeason::class);
    }

    public function events()
    {
        return $this->hasMany(Event::class);
    }

    public function seasonTickets()
    {
        return $this->hasMany(SeasonTicket::class);
    }
}
