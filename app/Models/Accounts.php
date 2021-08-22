<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Accounts extends Model {
    use HasFactory;

    protected $table = 'user_accounts';

    public $timestamps = true;

    protected $fillable = ['user_id', 'account_number', 'account_balance', 'account_type', 'bank_name'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function userTransactionHistory() {
        return $this->belongsToMany(User::class, 'transaction_ledger', 'account_id', 'user_id')->withPivot('transaction_type', 'transaction_amount', 'created_at', 'updated_at');
    }
    

}

