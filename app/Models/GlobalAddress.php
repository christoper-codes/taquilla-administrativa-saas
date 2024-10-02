<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GlobalAddress extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'address_line_1',
        'address_line_2',
        'city',
        'state',
        'zip_code',
        'country',
        'is_active',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function leadingCompany()
    {
        return $this->hasOne(LeadingCompany::class);
    }

    public function stadiums()
    {
        return $this->hasMany(Stadium::class);
    }

    public function ticketOffices()
    {
        return $this->hasMany(TicketOffice::class);
    }

}
