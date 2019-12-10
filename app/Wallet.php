<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    protected $fillable = [
        'address',
        'balance'
    ];

    public function transactions()
    {
        return $this->hasMany(Trainsaciton::class);
    }
}
