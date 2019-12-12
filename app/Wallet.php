<?php

namespace App;

use EthereumPHP\Types\Wei;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    const WALLET_PREFIX = "eth_address_";

    protected $fillable = [
        'address',
        'balance'
    ];

    protected $casts = [
        'balance' => 'string'
    ];
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function getBalanceAttribute()
    {
        return rtrim(rtrim($this->getOriginal('balance'), "0"),".");
    }

    public static function getWalletKey(string $address)
    {
        return self::WALLET_PREFIX . $address;
    }
}
