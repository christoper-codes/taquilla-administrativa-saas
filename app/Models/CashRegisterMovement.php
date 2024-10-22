<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashRegisterMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'cash_register_id',
        'cash_register_movement_type_id',
        'sale_ticket_id',
        'sale_ticket_cancelation_id',
        'previous_balance',
        'movement_amount',
        'new_balance',
        'is_active'
    ];

    public function cashRegister()
    {
        return $this->belongsTo(CashRegister::class);
    }

    public function cashRegisterMovementType()
    {
        return $this->belongsTo(CashRegisterMovementType::class);
    }

    public function saleTicket()
    {
        return $this->belongsTo(SaleTicket::class);
    }

    public function saleTicketCancelation()
    {
        return $this->belongsTo(SaleTicketCancelation::class);
    }

}
