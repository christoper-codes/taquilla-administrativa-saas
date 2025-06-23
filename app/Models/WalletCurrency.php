<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalletCurrency extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'symbol',
        'image_file',
        'is_active',
    ];

    public function walletAccounts()
    {
        return $this->hasMany(WalletAccount::class);
    }
}
