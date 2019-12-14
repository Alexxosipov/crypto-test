<?php

namespace App\Observers;

use App\Wallet;
use Illuminate\Support\Facades\Redis;

class WalletObserver
{
    /**
     * Handle the wallet "created" event.
     *
     * @param  \App\Wallet  $wallet
     * @return void
     */
    public function created(Wallet $wallet)
    {
        Redis::connection()->set(strtolower(Wallet::getWalletKey($wallet->address)), 1);
    }

    /**
     * Handle the wallet "deleted" event.
     *
     * @param  Wallet  $wallet
     * @return void
     */
    public function deleted(Wallet $wallet)
    {
        Redis::connection()->del([strtolower(Wallet::getWalletKey($wallet->address))]);
    }
}
