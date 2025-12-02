<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $fillable = [
        'user_id',
        'number',
        'name',
        'type',
        'currency',
        'balance',
        'status',
    ];

    // --------------------------
    // Relationships
    // --------------------------

    /**
     * Each account belongs to a single user (owner).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * An account can have many transactions.
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * An account can have many logs.
     */
    public function logs()
    {
        return $this->hasMany(AuditLog::class);
    }
}
