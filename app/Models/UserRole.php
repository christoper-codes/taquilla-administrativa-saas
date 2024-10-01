<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'is_active',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'role_user', 'user_role_id', 'user_id')
            ->withPivot('is_active')
            ->withTimestamps();
    }
}
