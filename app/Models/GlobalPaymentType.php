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
            ->withPivot('global_card_payment_type_id', 'reason_agreement_id', 'amount', 'original_amount', 'reason_courtesy', 'payment_date', 'is_active', 'installment_payment_history_id')
            ->withTimestamps();
    }

    public function installmentPaymentHistories()
    {
        return $this->belongsToMany(InstallmentPaymentHistory::class, 'global_payment_type_sale_ticket', 'global_payment_type_id', 'installment_payment_history_id')
        ->withPivot('global_card_payment_type_id', 'reason_agreement_id', 'amount', 'original_amount', 'reason_courtesy', 'payment_date', 'is_active')
        ->withTimestamps();
    }
}
