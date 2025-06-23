<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalletAccountType extends Model
{
    use HasFactory;

    protected $fillable = [
        'wallet_currency_id',
        'name',
        'color',
        'description',
        'is_active',
    ];

    public function walletCurrency()
    {
        return $this->belongsTo(WalletCurrency::class);
    }
}
