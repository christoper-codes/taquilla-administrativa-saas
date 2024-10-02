<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleTicketStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'is_active'
    ];

    public function saleTickets()
    {
        return $this->hasMany(SaleTicket::class);
    }
}
