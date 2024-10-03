<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashRegisterType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'is_active'
    ];

    public function ticketOffices()
    {
        return $this->belongsToMany(TicketOffice::class, 'cash_register_type_ticket_office', 'cash_register_type_id', 'ticket_office_id')
            ->withPivot('is_active')
            ->withTimestamps();
    }

    public function cashRegisters()
    {
        return $this->hasMany(CashRegister::class);
    }
}
