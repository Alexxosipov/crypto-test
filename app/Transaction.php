<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    const OPERATION_TYPE_IN = 'in';
    const OPERATION_TYPE_OUT = 'out';

    protected $fillable = ["wallet_id", "value", "operation_type", "to"];

    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }
}
