<?php


namespace App\Helpers;


use App\Transaction as TransactionModel;
use App\Wallet;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

class Transaction
{
    private $to = null;
    private $recipientModel = null;
    private $from = null;
    private $senderModel = null;
    private $transactionExists = false;
    private $amount = null;
    private $hash;
    private $redis;

    public function __construct(array $transaction)
    {
        $this->redis = Redis::connection();
        $this->to = $transaction['to'] ?? null;
        $this->from = $transaction['from'] ?? null;
        $this->hash = $transaction['hash'];
        $this->amount = isset($transaction['value']) ? EthHelper::getWeiFromHexDec($transaction['value']) : null;
        $this->initModels();
    }

    public function getRecipientModel() :?Wallet
    {
        return $this->recipientModel;
    }

    public function getSenderModel() :?Wallet
    {
        return $this->senderModel;
    }

    public function getRecipient() :?string
    {
        return $this->to ?? null;
    }

    public function getSender() :?string
    {
        return $this->from ?? null;
    }

    public function getAmount() :string
    {
        return $this->amount;
    }

    public function getHash() :string
    {
        return $this->hash;
    }


    private function initModels() :void
    {
        if ($this->from && $this->redis->get(Wallet::getWalletKey($this->from))) {
            $this->transactionExists = true;
            $this->senderModel = Wallet::whereAddress($this->from)->first();
        }

        if ($this->to && $this->redis->get(Wallet::getWalletKey($this->to))) {
            $this->transactionExists = true;
            $this->recipientModel = Wallet::whereAddress($this->to)->first();
        }
    }

    public function addressExistsInDatabase() :bool
    {
        return $this->transactionExists;
    }

    public function writeTransactionToDatabase()
    {
        if ($sender = $this->getSenderModel()) {
            TransactionModel::create([
                'hash' => $this->getHash(),
                'wallet_id' => $sender->id,
                'type' => TransactionModel::OPERATION_TYPE_OUT,
                'value' => $this->getAmount(),
                'to' => $this->getRecipient()
            ]);
        }

        if ($recipient = $this->getRecipientModel()) {
            TransactionModel::create([
                'hash' => $this->getHash(),
                'wallet_id' => $recipient->id,
                'type' => TransactionModel::OPERATION_TYPE_IN,
                'value' => $this->getAmount(),
                'to' => $this->getSender()
            ]);
        }
    }
}
