<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalletExchangeRate extends Model
{
    use HasFactory;

    protected $fillable = [
        'from_wallet_currency_id',
        'to_wallet_currency_id',
        'rate',
        'is_active',
    ];

    public function fromWalletCurrency()
    {
        return $this->belongsTo(WalletCurrency::class, 'from_wallet_currency_id');
    }

    public function toWalletCurrency()
    {
        return $this->belongsTo(WalletCurrency::class, 'to_wallet_currency_id');
    }
}
