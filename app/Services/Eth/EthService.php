<?php


namespace App\Services\Eth;


interface EthService
{
    public function getBalance(string $address) :?float;

    public function getHistory(string $address) :array;
}
