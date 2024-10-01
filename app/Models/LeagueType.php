<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeagueType extends Model
{
    use HasFactory;


    protected $fillable = [
        'global_image_id',
        'name',
        'description',
        'is_active',
    ];

    public function stadiums()
    {
        return $this->hasMany(GlobalSeason::class);
    }

    public function globalImage()
    {
        return $this->belongsTo(GlobalImage::class);
    }

    public function series()
    {
        return $this->hasMany(Serie::class);
    }

}
