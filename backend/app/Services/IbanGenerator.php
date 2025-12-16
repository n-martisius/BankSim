<?php

namespace App\Services;

use App\Models\Account;

class IbanGenerator
{
    public static function generate(): string
    {
        do {
            $iban = 'BS' . random_int(1000000000000000, 9999999999999999);
        } while (Account::where('number', $iban)->exists());

        return $iban;
    }
}
