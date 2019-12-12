<?php

namespace App\Console\Commands;

use App\Console\Tasks\ProcessTransactionTask;
use App\Services\Eth\EthService;
use Illuminate\Console\Command;
use Spatie\Async\Pool;

class ListenWebsocketCommand extends Command
{
    private $count = 0;
     /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'infura:listen';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Listen infura websocket data';

    private $ethService;
    private $pool;

    public function __construct(EthService $ethService)
    {
        parent::__construct();
        $this->ethService = $ethService;
        $this->pool = Pool::create();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $projectId = config('infura.id');
        \Ratchet\Client\connect("wss://mainnet.infura.io/ws/v3/{$projectId}")
            ->then(function($conn) {
                $req = json_encode([
                    'jsonrpc' => "2.0",
                    "id" => 1,
                    "method" => "eth_subscribe",
                    "params" => ["newPendingTransactions"]
                ]);
                $conn->send($req);
                $this->info('connected');

                $conn->on('message', function($msg) use ($conn) {
                    $this->info(rand(1,5));
                    $transaction = json_decode($msg, true);
                    if (isset($transaction['params'])) {
                        $transactionHash = $transaction['params']['result'];
                        $this->pool->add(new ProcessTransactionTask($transactionHash, $this->ethService));
                    }
                });

            }, function($error) {
                $this->error("Could not connect to the socket");
                $this->error($error);
            });
    }


}
