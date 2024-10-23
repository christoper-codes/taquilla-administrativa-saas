<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgreementPromotion extends Model
{
    use HasFactory;

    protected $table = 'agreement_promotion';

    protected $fillable = [
        'agreement_id',
        'promotion_id',
        'is_active'
    ];

    public function agreement()
    {
        return $this->belongsTo(Agreement::class);
    }

    public function promotion()
    {
        return $this->belongsTo(Promotion::class);
    }

    public function eventSeatCatalogSaleTickets()
    {
        return $this->hasMany(EventSeatCatalogSaleTicket::class);
    }
}
