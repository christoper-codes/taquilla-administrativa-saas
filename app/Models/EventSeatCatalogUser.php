<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventSeatCatalogUser extends Model
{
    use HasFactory;


    protected $table = 'event_seat_catalog_user';

    protected $fillable = [
        'event_seat_catalog_id',
        'sender_user_id',
        'receiver_user_id'
    ];
}
