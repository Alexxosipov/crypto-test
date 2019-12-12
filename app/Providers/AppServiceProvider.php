<?php

namespace App\Providers;

use App\Observers\TransactionObserver;
use App\Observers\WalletObserver;
use App\Services\Eth\EthService;
use App\Services\Eth\InfuraEthService;
use App\Transaction;
use App\Wallet;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * All of the container bindings that should be registered.
     *
     * @var array
     */
    public $bindings = [
        EthService::class => InfuraEthService::class,
    ];

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Wallet::observe(WalletObserver::class);
        Transaction::observe(TransactionObserver::class);
    }
}
