<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Institution extends Model
{
    use HasFactory;

    protected $fillable = [
        'stadium_id',
        'global_image_id',
        'name',
        'description',
        'is_active'
    ];

    public function stadium()
    {
        return $this->belongsTo(Stadium::class);
    }

    public function globalImage()
    {
        return $this->belongsTo(GlobalImage::class);
    }

    public function agreements()
    {
        return $this->hasMany(Agreement::class);
    }
}
