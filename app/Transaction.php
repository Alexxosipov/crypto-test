<?php

namespace App;

use App\Services\Eth\EthService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class Transaction extends Model
{
    const OPERATION_TYPE_IN = 'in';
    const OPERATION_TYPE_OUT = 'out';

    protected $fillable = ["wallet_id", "value", "type", "to", "hash", "block", "confirmations"];

    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }

    public function getValueAttribute()
    {
        return rtrim(rtrim($this->getOriginal('value'), "0"),".");
    }

    public function getType()
    {
        return $this->type;
    }
}
