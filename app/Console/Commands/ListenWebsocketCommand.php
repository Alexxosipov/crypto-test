<?php

namespace App\Console\Commands;

use App\Services\Eth\EthService;
use App\Websocket\AsyncTasks\ProcessNewHeadEthereumTask;
use Illuminate\Console\Command;
use Spatie\Async\Pool;

class ListenWebsocketCommand extends Command
{
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
        $network = config('infura.network');
        \Ratchet\Client\connect("wss://{$network}.infura.io/ws/v3/{$projectId}")
            ->then(function($conn) {
                $req = json_encode([
                    'jsonrpc' => "2.0",
                    "id" => 1,
                    "method" => "eth_subscribe",
                    "params" => ["newHeads"]
                ]);
                $conn->send($req);
                $this->info('Connected to INFURA');

                $conn->on('message', function($msg) use ($conn) {
                    $transaction = json_decode($msg, true);
                    if (isset($transaction['params'])) {
                        $blockHash = $transaction['params']['result']['hash'];
                        $blockNumber = $transaction['params']['result']['number'];
                        $this->pool->add(new ProcessNewHeadEthereumTask($blockHash, $blockNumber, $this->ethService));
                    }
                });

            }, function($error) {
                $this->error("Could not connect to the socket");
                $this->error($error);
            });
    }


}
