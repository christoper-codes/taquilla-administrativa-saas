<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GlobalPaymentTypeSaleTicket extends Model
{
    protected $table = 'global_payment_type_sale_ticket';

    protected $fillable = [
        'global_payment_type_id',
        'sale_ticket_id',
        'global_card_payment_type_id',
        'courtesy_type_id',
        'reason_agreement_id',
        'installment_payment_history_id',
        'amount',
        'original_amount',
        'reason_courtesy',
        'original_amount',
        'payment_date',
        'is_active'
    ];

    public function globalCardPaymentType()
    {
        return $this->belongsTo(GlobalCardPaymentType::class, 'global_card_payment_type_id');
    }
}
