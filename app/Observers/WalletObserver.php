<?php

namespace App\Observers;

use App\Wallet;
use Illuminate\Support\Facades\Cache;

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
        Cache::forever(Wallet::getWalletKey($wallet->address), 1);
    }

    /**
     * Handle the wallet "updated" event.
     *
     * @param  Wallet  $wallet
     * @return void
     */
    public function updated(Wallet $wallet)
    {
        //
    }

    /**
     * Handle the wallet "deleted" event.
     *
     * @param  Wallet  $wallet
     * @return void
     */
    public function deleted(Wallet $wallet)
    {
        Cache::forget(Wallet::getWalletKey($wallet->address));
    }

    /**
     * Handle the wallet "restored" event.
     *
     * @param  \App\Wallet  $wallet
     * @return void
     */
    public function restored(Wallet $wallet)
    {
        //
    }

    /**
     * Handle the wallet "force deleted" event.
     *
     * @param  Wallet  $wallet
     * @return void
     */
    public function forceDeleted(Wallet $wallet)
    {
        //
    }
}
