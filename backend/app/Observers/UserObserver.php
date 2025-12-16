<?php

namespace App\Observers;

use App\Models\User;
use App\Models\Account;
use App\Services\IbanGenerator;

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user)
    {
        if ($user->role !== 'customer') {
            return;
        }

        Account::create([
            'user_id' => $user->id,
            'number' => IbanGenerator::generate(),
            'balance' => 0.00,
            'currency' => 'EUR',
            'name' => "{$user->full_name}'s Main Account",
            'type' => 'debit',
        ]);
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        //
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        //
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        //
    }
}
