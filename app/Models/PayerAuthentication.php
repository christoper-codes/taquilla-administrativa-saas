<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayerAuthentication extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'transaction_id',
        'cardType',
        'enroll_payload',
        'enroll_response',
        'challenge_payload',
        'challenge_response',
        'payment_payload',
        'payment_response',
    ];

    protected $casts = [
        'enroll_payload' => 'array',
        'enroll_response' => 'array',
        'challenge_payload' => 'array',
        'challenge_response' => 'array',
        'payment_payload' => 'array',
        'payment_response' => 'array',
    ];
}
