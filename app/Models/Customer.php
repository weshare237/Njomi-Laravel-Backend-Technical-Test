<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'sex',
        'date_of_birth',
        'place_of_birth',
        'nationality',
        'country_of_origin',
        'address_line_1',
        'address_line_2',
        'phone',
        'email',
        'created_at_branch',
        'created_by',
        'lastly_updated_by'
    ];

    protected function createdAtBranch() {
        return $this->hasOne(BankBranch::class, 'id', 'created_at_branch');
    }

    protected function createdBy() {
        return $this->hasOne(User::class, 'id', 'created_by');
    }

    protected function lastlyUpdatedBy() {
        return $this->hasOne(User::class, 'id', 'lastly_updated_by');
    }

}
