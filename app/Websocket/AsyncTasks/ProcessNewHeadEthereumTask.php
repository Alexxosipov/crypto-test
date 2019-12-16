<?php

namespace App\Websocket\AsyncTasks;

use App\Helpers\Transaction;
use App\Services\Eth\EthService;
use Illuminate\Support\Facades\DB;
use Spatie\Async\Task;

class ProcessNewHeadEthereumTask extends Task
{
    use Laravelable;

    private $ethService;
    private $blockHash;
    private $blockNumber;

    public function __construct(string $blockHash, string $blockNumber, EthService $ethService)
    {
        $this->blockHash = $blockHash;
        $this->blockNumber = $blockNumber;
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
        if (count($block['transactions'])) {
            foreach ($block['transactions'] as $transactionArray) {
                $transaction = new Transaction($transactionArray, $this->blockNumber);
                if ($transaction->addressExistsInDatabase()) {
                    $transaction->writeTransactionToDatabase();
                }
            }
        }

        DB::table('transactions')->increment('confirmations', 1, [
            'block' => $this->blockNumber
        ]);
    }
}
