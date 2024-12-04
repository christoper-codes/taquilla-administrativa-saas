<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesTicketCancellationCode extends Model
{
    use HasFactory;

    protected $table = 'sales_ticket_cancellation_codes';

    protected $fillable = [
        'ticket_office_id',
        'cancellation_code',
        'description',
        'is_active'
    ];

    public function ticketOffice()
    {
        return $this->belongsTo(TicketOffice::class);
    }
}
