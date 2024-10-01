<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agreement extends Model
{
    use HasFactory;

    protected $fillable = [
        'institution_id',
        'price_type_id',
        'global_season_id',
        'name',
        'description',
        'start_date',
        'end_date',
        'is_active'
    ];

    public function institution()
    {
        return $this->belongsTo(Institution::class);
    }

    public function priceType()
    {
        return $this->belongsTo(PriceType::class);
    }

    public function globalSeason()
    {
        return $this->belongsTo(GlobalSeason::class);
    }
}
