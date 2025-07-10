<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalletTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'wallet_account_id',
        'wallet_transaction_status_id',
        'wallet_transaction_type_id',
        'wallet_recharge_amount_id',
        'pos_cash_register_id',
        'pos_ticket_id',
        'description',
        'movement_amount',
        'cash_back_generated',
        'paid_with_cashless',
        'paid_with_cashback',
        'cashback_balance_account_before_transaction',
        'cashback_balance_account_after_transaction',
        'cashless_balance_account_before_transaction',
        'cashless_balance_account_after_transaction',
        'related_transaction_id',
    ];

    /**
     * Get the walletAccount that owns the WalletTransaction
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function walletAccount()
    {
        return $this->belongsTo(WalletAccount::class);
    }

    public function walletTransactionStatus()
    {
        return $this->belongsTo(WalletTransactionStatus::class);
    }

    public function walletTransactionType()
    {
        return $this->belongsTo(WalletTransactionType::class);
    }

    public function walletRechargeAmount()
    {
        return $this->belongsTo(WalletRechargeAmount::class);
    }

    public function globalPaymentTypes()
    {
        return $this->belongsToMany(GlobalPaymentType::class, 'global_payment_type_sale_ticket', 'wallet_transaction_id', 'global_payment_type_id')
            ->withPivot('global_card_payment_type_id', 'amount', 'is_active')
            ->withTimestamps();
    }

    public function relatedTransaction()
    {
        return $this->belongsTo(WalletTransaction::class, 'related_transaction_id');
    }
}
