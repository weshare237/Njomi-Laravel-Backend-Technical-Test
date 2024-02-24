<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'account_number',
        'account_type',
        'account_balance',
        'customer_id',
        'created_at_branch',
        'created_by'
    ];

    protected function owner() {
        return $this->hasOne(Customer::class, 'id', 'customer_id');
    }

    protected function createdAtBranch() {
        return $this->hasOne(BankBranch::class, 'id', 'created_at_branch');
    }

    protected function createdBy() {
        return $this->hasOne(User::class, 'id', 'created_by');
    }

}
