<?php

namespace App\Http\Controllers;

use App\Http\Requests\Wallet\StoreWalletRequest;
use App\Services\Eth\EthService;
use App\Wallet;
use Illuminate\Http\Request;

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
        Wallet::create([
            'address' => $request->post('address'),
            'balance' => $ethService->getBalance($request->post('address'))
        ]);

        return redirect()->back()->with('success', self::SUCCESS_WALLET_SAVE_MESSAGE);
    }
}
