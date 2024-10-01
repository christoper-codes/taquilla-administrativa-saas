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
}
