<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleDebtor extends Model
{
    use HasFactory;

    protected $fillable = [
        'stadium_id',
        'first_name',
        'last_name',
        'phone_number',
        'email',
        'is_active',
    ];

    public function stadium()
    {
        return $this->belongsTo(Stadium::class);
    }

    public function saleTickets()
    {
        return $this->hasMany(SaleTicket::class);
    }
}
