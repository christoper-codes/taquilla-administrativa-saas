<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GlobalImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'file_name',
        'file_path',
        'file_extension',
        'file_size',
        'file_type',
        'is_active',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'global_image_user', 'global_image_id', 'user_id')
            ->withPivot('is_active')
            ->withTimestamps();
    }

    public function leadingCompany()
    {
        return $this->hasOne(LeadingCompany::class);
    }

    public function stadiums()
    {
        return $this->belongsToMany(Stadium::class, 'global_image_stadium', 'global_image_id', 'stadium_id')
                    ->withPivot('is_active')
                    ->withTimestamps();
    }

    public function globalSeason()
    {
        return $this->hasOne(GlobalSeason::class);
    }

    public function zoneType()
    {
        return $this->hasOne(ZoneType::class);
    }

    public function institution()
    {
        return $this->hasOne(Institution::class);
    }

    public function leagueType()
    {
        return $this->hasOne(LeagueType::class);
    }

    public function event()
    {
        return $this->hasOne(Event::class);
    }

    public function ticketOffice()
    {
        return $this->hasOne(TicketOffice::class);
    }
}
