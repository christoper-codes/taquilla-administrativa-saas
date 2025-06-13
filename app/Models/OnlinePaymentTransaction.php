<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OnlinePaymentTransaction extends Model
{
    protected $casts = [
        'data' => 'array',
    ];

    protected $fillable = [
        'sale_ticket_id',
        'data',
        'source',
        'is_active',
    ];

    public function saleTicket()
    {
        return $this->belongsTo(SaleTicket::class);
    }
}
