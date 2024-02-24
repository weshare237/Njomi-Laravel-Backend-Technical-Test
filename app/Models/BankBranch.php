<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankBranch extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address_line_1',
        'address_line_2',
        'state',
        'country',
        'bank_id'
    ];

    public function bank() {
        return $this->belongsTo(Bank::class, 'id');
    }

}
