<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalletAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'account_number',
        'is_active'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function walletAccountRole()
    {
        return $this->belongsToMany(WalletAccountRole::class, 'account_role_wallet_account', 'wallet_account_id', 'wallet_account_role_id')->withTimestamps();
    }

    public function walletAccountTypes()
    {
        return $this->belongsToMany(WalletAccountType::class)->using(WalletAccountWalletAccountType::class)->withPivot('id','current_balance', 'is_active', 'expiration_date');
    }

    public function seasonTickets()
    {
        return $this->belongsToMany(SeasonTicket::class, 'season_ticket_wallet_account','wallet_account_id', 'season_ticket_id');
    }

    /**
     * Get all of the walletTransaction for the WalletAccount
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function walletTransaction()
    {
        return $this->hasMany(WalletTransaction::class);
    }

    /**
     * Get all of the wallet_account_privilege_histories for the WalletAccount
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function walletAccountPrivilegeHistory()
    {
        return $this->hasMany(WalletAccountPrivilegeHistory::class)->where('is_active', true);
    }

    /**
     * Get all of the wallet_account_privilege_histories for the WalletAccount
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function walletAccountPrivilegeHistories()
    {
        return $this->hasMany(WalletAccountPrivilegeHistory::class);
    }
}
