<?php

namespace App\Observers;

use App\Transaction;
use App\Websocket\WebsocketPusher;

class TransactionObserver
{
    public function created(Transaction $transaction)
    {
        WebsocketPusher::push($transaction->toJson());
    }
}
