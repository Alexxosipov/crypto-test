<?php


namespace App\Console\Tasks;

use App\Console\Kernel;
use App\Helpers\EthHelper;
use App\Helpers\Transaction;
use App\Services\Eth\EthService;
use App\Transaction as TransactionModel;
use App\Wallet;
use Spatie\Async\Task;

class ProcessTransactionTask extends Task
{
    private $transactionHash;
    private $ethService;
    private $app;

    public function __construct(string $transactionHash, EthService $ethService)
    {
        $this->ethService = $ethService;
        $this->transactionHash = $transactionHash;
    }

    public function configure()
    {
        $this->app = require __DIR__.'/../../../bootstrap/app.php';
        $this->app->make(Kernel::class)->bootstrap();
    }

    public function run()
    {

        $transaction = $this->ethService->getTransaction($this->transactionHash);
        if ($transaction) {
            $transaction = new Transaction($transaction);
            if ($transaction->addressExistsInDatabase()) {
                if ($sender = $transaction->getSenderModel()) {
                    TransactionModel::create([
                        'wallet_id' => $sender->id,
                        'type' => TransactionModel::OPERATION_TYPE_OUT,
                        'value' => $transaction->getAmount(),
                        'to' => $transaction->getRecipient()
                    ]);
                }

                if ($recipient = $transaction->getRecipientModel()) {
                    TransactionModel::create([
                        'wallet_id' => $sender->id,
                        'type' => TransactionModel::OPERATION_TYPE_IN,
                        'value' => $transaction->getAmount(),
                        'to' => $transaction->getSender()
                    ]);
                }
            }
        }

    }
}
