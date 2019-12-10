<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Trainsaciton extends Model
{
    const OPERATION_TYPE_IN = 'in';
    const OPERATION_TYPE_OUT = 'out';

    protected $fillable = [
        'from',
        'amount',
        'operation_type'
    ];

    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }
}
