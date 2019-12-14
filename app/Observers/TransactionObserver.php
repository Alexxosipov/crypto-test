<?php

namespace App\Observers;

use App\Transaction;
use App\Wallet;
use Illuminate\Support\Facades\Log;

class TransactionObserver
{
    public function created(Transaction $transaction)
    {
        $transaction->wallet->updateBalance();
    }
}
