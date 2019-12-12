<?php


namespace App\Services\Eth;


interface EthService
{
    public function getBalance(string $address) :?string;

    public function getHistory(string $address) :array;

    public function getTransaction(string $transactionHash);
}
