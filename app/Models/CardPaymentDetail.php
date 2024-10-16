<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CardPaymentDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_sale_ticket',
        'full_name_user',
        'email_address',
        'id_billing',
        'status',
        'amount',
    ];
}
