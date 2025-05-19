<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    use HasFactory;

    protected $fillable = [
        'promotion_type_id',
        'stadium_id',
        'name',
        'description',
        'generic_seats_allowed',
        'promotional_seats_allowed',
        'maximun_promotions_allowed',
        'percent_allow',
        'is_active',
        'is_active_online',
        'availability_sale'
    ];

    public function promotionType()
    {
        return $this->belongsTo(PromotionType::class);
    }

    public function stadium()
    {
        return $this->belongsTo(Stadium::class);
    }

    public function agreements()
    {
        return $this->belongsToMany(Agreement::class, 'agreement_promotion', 'promotion_id', 'agreement_id')
                    ->withPivot('id','is_active')
                    ->withTimestamps();
    }

    public function eventSeatCatalogs()
    {
        return $this->belongsToMany(EventSeatCatalog::class, 'event_seat_catalog_promotion', 'promotion_id', 'event_seat_catalog_id')
                    ->withPivot('is_active')
                    ->withTimestamps();
    }

    public function saleTickets()
    {
        return $this->hasMany(SaleTicket::class);
    }

}
