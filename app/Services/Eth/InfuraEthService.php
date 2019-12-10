<?php


namespace App\Services\Eth;

use BCMathExtended\BC;
use Bezhanov\Ethereum\Converter;
use Graze\GuzzleHttp\JsonRpc\Client;

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

    public function getBalance(string $address): ?float
    {
        $response = $this->rpcClient->send($this->rpcClient->request(1, 'eth_getBalance', [$address, 'latest']));
        $hexInt = BC::hexdec($response->getRpcResult());
        return $this->converter->fromWei($hexInt);
    }

    public function getHistory(string $address): array
    {
        // TODO: Implement getHistory() method.
        return [];
    }
}
