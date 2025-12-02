<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'full_name',
        'password',
        'phone',
        'role',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // ----------------------
    // Relationships
    // ----------------------

    /**
     * A user can have multiple bank accounts.
     */
    public function accounts()
    {
        return $this->hasMany(Account::class);
    }

    /**
     * A user may have performed multiple transactions.
     * (Optional — if your transactions table has a user_id)
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * A user may have created many log entries.
     * (Optional)
     */
    public function logs()
    {
        return $this->hasMany(AuditLog::class);
    }

    // ----------------------
    // Role helpers
    // ----------------------

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isTeller()
    {
        return $this->role === 'teller';
    }

    public function isCustomer()
    {
        return $this->role === 'customer';
    }
}
