<?php


namespace App\Helpers;


use App\Wallet;
use Illuminate\Support\Facades\Redis;

class Transaction
{
    private $to = null;
    private $toModel = null;
    private $from = null;
    private $fromModel = null;
    private $transactionExists = false;
    private $amount = null;
    private $redis;

    public function __construct(array $transaction)
    {
        $this->redis = Redis::connection();
        $this->to = $transaction['to'] ?? null;
        $this->from = $transaction['from'] ?? null;
        $this->amount = isset($transaction['value']) ? EthHelper::getWeiFromHexDec($transaction['value']) : null;
        $this->initModels();
    }

    public function getRecipientModel() :?Wallet
    {
        if ($this->to && $model = Wallet::where('address', $this->to)->first()) {
            return $model;
        }
        return null;
    }

    public function getSenderModel() :?Wallet
    {
        if ($this->from && $model = Wallet::whereAddress('address', $this->from)->first()) {
            return $model;
        }
        return null;
    }

    public function getRecipient() :?string
    {
        return $this->to ?? null;
    }

    public function getSender() :?string
    {
        return $this->from ?? null;
    }

    private function initModels() :void
    {
        if ($this->redis->get(Wallet::getWalletKey($this->from))) {
            $this->transactionExists = true;
            $this->fromModel = Wallet::whereAddress($this->from)->first();
        }

        if ($this->redis->get(Wallet::getWalletKey($this->to))) {
            $this->transactionExists = true;
            $this->toModel = Wallet::whereAddress($this->to)->first();
        }
    }

    public function addressExistsInDatabase() :bool
    {
        return $this->transactionExists;
    }

    public function getAmount() :?string
    {
        return $this->amount ? EthHelper::getWeiFromHexDec($this->amount) : null;
    }
}
