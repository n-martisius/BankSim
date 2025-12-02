<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'user_id',
        'from_account_id',
        'to_account_id',
        'type',
        'amount',
        'currency',
        'status',
        'details',
    ];

    // ---------------------------
    // Relationships
    // ---------------------------

    // User who initiated the transaction
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Account the money came from
    public function fromAccount()
    {
        return $this->belongsTo(Account::class, 'from_account_id');
    }

    // Account the money went to
    public function toAccount()
    {
        return $this->belongsTo(Account::class, 'to_account_id');
    }
}
