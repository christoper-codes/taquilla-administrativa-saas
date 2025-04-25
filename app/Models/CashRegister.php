<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashRegister extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_office_id',
        'cash_register_type_id',
        'seller_user_opening_id',
        'seller_user_closing_id',
        'description',
        'is_open',
        'confirmed_closure',
        'batch_cash_register',
        'batch_code',
        'opening_balance',
        'current_balance',
        'closing_balance'
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function ticketOffice()
    {
        return $this->belongsTo(TicketOffice::class);
    }

    public function cashRegisterType()
    {
        return $this->belongsTo(CashRegisterType::class);
    }

    public function sellerUserOpening()
    {
        return $this->belongsTo(User::class, 'seller_user_opening_id');
    }

    public function sellerUserClosing()
    {
        return $this->belongsTo(User::class, 'seller_user_closing_id');
    }

    public function saleTickets()
    {
        return $this->hasMany(SaleTicket::class);
    }

    public function cashRegisterMovements()
    {
        return $this->hasMany(CashRegisterMovement::class);
    }

    public function installmentPaymentHistories()
    {
        return $this->hasMany(InstallmentPaymentHistory::class);
    }

}
