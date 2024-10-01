<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadingCompany extends Model
{
    use HasFactory;

    protected $fillable = [
        'global_image_id',
        'global_address_id',
        'name',
        'email',
        'phone_number',
        'description',
        'is_active',
    ];

    public function globalImage()
    {
        return $this->belongsTo(GlobalImage::class);
    }

    public function globalAddress()
    {
        return $this->belongsTo(GlobalAddress::class);
    }

    public function stadiums()
    {
        $this->hasMany(Stadium::class);
    }
}
