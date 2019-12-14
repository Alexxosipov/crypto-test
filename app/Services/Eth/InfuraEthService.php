<?php

namespace App\Services\Eth;

use App\Helpers\EthHelper;
use BCMathExtended\BC;
use Bezhanov\Ethereum\Converter;
use EthereumPHP\Types\Wei;
use Graze\GuzzleHttp\JsonRpc\Client;
use http\Exception\InvalidArgumentException;
use Illuminate\Support\Facades\Log;

class InfuraEthService implements EthService
{
    private $rpcClient;
    private $converter;

    public function __construct()
    {
        $network = config('infura.network');
        $id = config('infura.id');
        $this->rpcClient = Client::factory("https://{$network}.infura.io/v3/{$id}");
        $this->converter = new Converter();
    }

    public function getBalance(string $address): ?string
    {
        $response = $this->rpcClient->send($this->rpcClient->request(1, 'eth_getBalance', [$address, 'latest']));
        return EthHelper::getWeiFromHexDec($response->getRpcResult());
    }

    public function getTransaction(string $transactionHash)
    {
        $request = $this->rpcClient->send(
            $this->rpcClient->request(2, 'eth_getTransactionByHash', [$transactionHash])
        );
        return $request->getRpcResult();
    }

    public function getHistory(string $address): array
    {
        // TODO: Implement getHistory() method.
        return [];
    }

    public function getBlock(string $blockHash) :?array
    {
        $req = $this->rpcClient->send(
            $this->rpcClient->request(3, 'eth_getBlockByHash', [$blockHash, true])
        );

        return $req->getRpcResult();
    }
}
