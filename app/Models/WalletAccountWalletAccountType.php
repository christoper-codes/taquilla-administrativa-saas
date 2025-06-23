<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class WalletAccountWalletAccountType extends Pivot
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'wallet_account_wallet_account_type';

    protected $fillable = [
        'wallet_account_id',
        'wallet_account_type_id',
        'current_balance',
        'is_active',
        'expiration_date',
    ];

    public function walletAccount()
    {
        return $this->belongsTo(WalletAccount::class);
    }

    public function walletAccountType()
    {
        return $this->belongsTo(WalletAccountType::class);
    }

    /**
     * Get all of the comments for the WalletAccountWalletAccountType
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function walletTransactions()
    {
        return $this->hasMany(WalletTransaction::class, 'wallet_account_wallet_account_type_id','id');
    }

}



