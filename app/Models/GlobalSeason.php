<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GlobalSeason extends Model
{
    use HasFactory;

    protected $fillable = [
        'stadium_id',
        'global_image_id',
        'global_season_type_id',
        'league_type_id',
        'name',
        'start_date',
        'end_date',
        'description',
        'enabled_for_season_tickets',
        'is_active',
    ];


    public function stadium()
    {
        return $this->belongsTo(Stadium::class);
    }

    public function globalImage()
    {
        return $this->belongsTo(GlobalImage::class);
    }

    public function globalSeasonType()
    {
        return $this->belongsTo(GlobalSeasonType::class);
    }

    public function leagueType()
    {
        return $this->belongsTo(LeagueType::class);
    }

    public function series()
    {
        return $this->hasMany(Serie::class);
    }

    public function events()
    {
        return $this->hasMany(Event::class);
    }

    public function agreements()
    {
        return $this->hasMany(Agreement::class);
    }


    public function seasonTickets()
    {
        return $this->hasMany(SeasonTicket::class);
    }

}
