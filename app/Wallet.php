<?php

namespace App;

use App\Services\Eth\EthService;
use EthereumPHP\Types\Wei;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

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

    /**
     * @var $ethService EthService
     */
    private $ethService;

    public function __construct(array $attributes = [])
    {
        $this->ethService = App::make(EthService::class);
        parent::__construct($attributes);
    }

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

    public function setAddressAttribute($value)
    {
        $this->attributes['address'] = strtolower($value);
    }

    public function updateBalance()
    {
        $this->balance = $this->ethService->getBalance($this->address);
        $this->save();
    }
}
