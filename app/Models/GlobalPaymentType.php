<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GlobalPaymentType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'is_active'
    ];

    public function saleTickets()
    {
        return $this->belongsToMany(SaleTicket::class, 'global_payment_type_sale_ticket', 'global_payment_type_id', 'sale_ticket_id')
            ->withPivot('global_card_payment_type_id', 'amount', 'original_amount', 'is_active')
            ->withTimestamps();
    }
}
