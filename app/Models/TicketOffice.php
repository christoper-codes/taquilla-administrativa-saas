<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketOffice extends Model
{
    use HasFactory;

    protected $fillable = [
        'stadium_id',
        'global_image_id',
        'global_address_id',
        'name',
        'description',
        'is_active'
    ];

    public function stadium()
    {
        return $this->belongsTo(Stadium::class);
    }

    public function globalImage()
    {
        return $this->belongsTo(GlobalImage::class);
    }

    public function globalAddress()
    {
        return $this->belongsTo(GlobalAddress::class);
    }

    public function cashRegisterTypes()
    {
        return $this->belongsToMany(CashRegisterType::class, 'cash_register_type_ticket_office', 'ticket_office_id', 'cash_register_type_id')
            ->withPivot('is_active')
            ->withTimestamps();
    }

    public function cashRegisters()
    {
        return $this->hasMany(CashRegister::class);
    }

    public function cashRegistersActives()
    {
        return $this->hasMany(CashRegister::class)->where('is_open', true);
    }
}
