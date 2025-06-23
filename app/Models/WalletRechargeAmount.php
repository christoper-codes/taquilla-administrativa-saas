<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalletRechargeAmount extends Model
{
    use HasFactory;

    protected $fillable = [
        'amount',
        'description',
        'is_active'
    ];

    public function walletTransactions()
    {
        return $this->hasMany(WalletTransaction::class);
    }
}
