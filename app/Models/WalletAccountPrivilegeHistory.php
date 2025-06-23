<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalletAccountPrivilegeHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'wallet_account_id',
        'name',
        'code',
        'percentage_cashback',
        'description',
        'is_active',
    ];


    /**
     * Get the WalletAccount that owns the WalletAccountPrivilegeHistory
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function walletAccount()
    {
        return $this->belongsTo(WalletAccount::class);
    }
}
