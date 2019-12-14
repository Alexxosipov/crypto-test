<?php

namespace App\Websocket\AsyncTasks;

use App\Helpers\Transaction;
use App\Services\Eth\EthService;
use Spatie\Async\Task;

class ProcessNewHeadEthereumTask extends Task
{
    use Laravelable;

    private $ethService;
    private $blockHash;

    public function __construct(string $blockHash, EthService $ethService)
    {
        $this->blockHash = $blockHash;
        $this->ethService = $ethService;
    }

    public function configure()
    {
        $this->setContainer();
    }

    public function run()
    {
        $this->getBlockData();
    }

    private function getBlockData()
    {
        $block = $this->ethService->getBlock($this->blockHash);
        foreach ($block['transactions'] as $transactionArray) {
            $transaction = new Transaction($transactionArray);
            if ($transaction->addressExistsInDatabase()) {
                $transaction->writeTransactionToDatabase();
            }
        }

    }
}
