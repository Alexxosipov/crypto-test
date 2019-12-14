@extends('layouts.app')

@push('styles')
    <style>
        table td {
            width: 200px;
            padding: 10px;
            white-space: nowrap;
            overflow: hidden;
            display: inline-block;
        }
        table .cell {
            text-overflow: ellipsis;
        }
        table .transaction__type {
            width: 70px;
        }
        table .transaction__value {
            width: auto;
        }
    </style>
@endpush
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="mb-3">
                    <a href="{{route('wallet.index')}}" class="btn btn-primary btn-small">Back</a>
                </div>
                <div class="card">
                    <div class="card-header">Wallet {{$wallet->address}}</div>

                    <div class="card-body">
                        <div class="mb-5">
                            <p>Balance: <span id="wallet-balance">{{$wallet->balance}}</span></p>
                        </div>
                        <h4>Transactions:</h4>
                        @if($wallet->transactions)
                        <table>
                            <thead>
                            <tr>
                                <td class="cell">Hash</td>
                                <td class="cell transaction__type">Type</td>
                                <td class="cell">Address</td>
                                <td class="value">Value</td>
                            </tr>
                            </thead>
                            <tbody id="transactions-table">
                                @foreach($wallet->transactions as $transaction)
                                    <tr data-id="{{$transaction->id}}">
                                        <td class="cell transaction__hash" title="{{$transaction->hash}}">{{$transaction->hash}}</td>
                                        <td class="cell transaction__type">{{$transaction->getType()}}</td>
                                        <td class="cell transaction__address" title="{{$transaction->to}}">{{$transaction->to}}</td>
                                        <td class="transaction__value">{{$transaction->value}}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                            @else
                            <p>There's no transactions on this wallet yet.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        setInterval(function(){
            $.ajax({
                method: 'get',
                url: '{{route('wallet.transactions', ['wallet' => $wallet])}}',
                dataType: 'json',
                success: function(data) {
                    var transactionsTable = $('#transactions-table');
                    var transactions = data.transactions.reverse();
                    var currentTransactionsIds = $.map(transactionsTable.children('tr'), function(item, index){
                        return $(item).attr('data-id');
                    });

                    transactions.forEach(function(item) {
                       if (!currentTransactionsIds.includes(item.id.toString())) {
                           transactionsTable.prepend(`
                            <tr data-id="${item.id}">
<td class="cell transaction__hash">${item.hash}</td>
<td class="cell transaction__type">${item.operation_type}</td>
<td class="cell transaction__address">${item.to}</td>
<td class="transaction__value">${item.value}</td>
</tr>
                           `);
                       }
                    });

                    var balance = $('#wallet-balance');
                    if (data.balance !== balance.text()) {
                        balance.text(data.balance);
                    }
                }
            });
        }, 5000);
    </script>
@endpush
