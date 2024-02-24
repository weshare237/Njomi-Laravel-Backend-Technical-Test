<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'bank_account_id',
        'transaction_type',
        'transaction_action',
        'reference',
        'amount',
        'charges',
        'total_transaction_amount',
        'account_balance_after_transaction',
        'targeted_external_bank_account_id',
        'comments'
    ];

    protected function bankAccount() {
        $this->hasOne(BankAccount::class, "id", "bank_account_id");
    }

    protected function targetedExternalBankAccount() {
        $this->hasOne(BankAccount::class, "id", "targeted_external_bank_account_id");
    }
}
