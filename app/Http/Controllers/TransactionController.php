<?php

namespace App\Http\Controllers;

use App\Services\Eth\EthService;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function findTransaction(Request $request, EthService $ethService)
    {
        dd($ethService->getTransaction($request->get('transaction')));
    }
}
