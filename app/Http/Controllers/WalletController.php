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

    public function index(Request $request)
    {
        $wallets = Wallet::all();
        return view('wallet.index', compact('wallets'));
    }

    public function apiIndex()
    {
        $wallets = Wallet::all();
        return response()->json($wallets);
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

    public function get(Wallet $wallet)
    {
        $wallet->load('transactions');
        return view('wallet.wallet', compact('wallet'));
    }

    public function transactions(Wallet $wallet)
    {
        return response()->json(['transactions' => $wallet->transactions, 'balance' => $wallet->balance]);
    }
}
