<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserGender extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'color',
        'is_active',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
