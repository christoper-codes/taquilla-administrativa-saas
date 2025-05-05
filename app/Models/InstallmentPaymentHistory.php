<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InstallmentPaymentHistory extends Model
{
    protected $table = 'installment_payment_histories';

    protected $fillable = [
        'sale_ticket_id',
        'cash_register_id',
        'amount_received',
        'total_amount',
        'total_returned',
        'is_active',
    ];

    public function saleTicket()
    {
        return $this->belongsTo(SaleTicket::class);
    }

    public function globalPaymentTypes()
    {
        return $this->belongsToMany(GlobalPaymentType::class, 'global_payment_type_sale_ticket', 'installment_payment_history_id', 'global_payment_type_id')
            ->withPivot('global_card_payment_type_id', 'amount', 'reason_courtesy', 'original_amount', 'payment_date', 'is_active')
            ->withTimestamps();
    }

    public function cashRegister()
    {
        return $this->belongsTo(CashRegister::class);
    }
}
