<?php

namespace App\Http\Controllers;

use App\Http\Requests\Wallet\StoreWalletRequest;
use App\Observers\WalletObserver;
use App\Services\Eth\EthService;
use App\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class WalletController extends Controller
{
    // it should be refactored with translation dictionaries
    const SUCCESS_WALLET_SAVE_MESSAGE = 'Wallet successfully saved.';

    public function index(Request $request, EthService $ethService)
    {
        $wallets = Wallet::all();
        return view('wallet.index', compact('wallets'));
    }

    public function create()
    {
        return view('wallet.create');
    }

    public function store(StoreWalletRequest $request, EthService $ethService)
    {
        $balance = $ethService->getBalance($request->post('address'));
        Wallet::create([
            'address' => $request->post('address'),
            'balance' => $balance
        ]);

        return redirect(route('wallet.index'))->with('success', self::SUCCESS_WALLET_SAVE_MESSAGE);
    }

    public function getBalance(Request $request, EthService $ethService)
    {
        $wallets = Wallet::all();
        $res = [];
        foreach ($wallets as $wallet) {
            //Redis::set(WalletObserver::getWalletKey($wallet->address), 1);
            $res[$wallet->address] = Redis::get(WalletObserver::getWalletKey($wallet->address));
        }
        dd($res);

//        dd($ethService->getBalance($request->get('address')));
    }
}
