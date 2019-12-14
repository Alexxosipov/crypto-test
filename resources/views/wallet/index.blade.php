@extends('layouts.app')

@push('styles')
    <style>
        .balance {
            background: white;
            transition: 2s;
        }
        .update-balance {
            background: #97ffae;
        }
    </style>
@endpush

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Wallets</div>

                    <div class="card-body">
                        @if($wallets->isNotEmpty())
                        <table class="table">
                            <thead>
                            <tr>
                                <th>Wallet address</th>
                                <th>Balance, ETH</th>
                            </tr>
                            </thead>
                            <tbody id="wallets-table">
                                @foreach($wallets as $wallet)
                                    <tr data-id="{{$wallet->id}}">
                                        <td><a href="{{route('wallet.get', ['wallet' => $wallet])}}">{{$wallet->address}}</a></td>
                                        <td class="balance">{{$wallet->balance}}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                            @else
                        There is no wallet yet
                            @endif
                            <div class="mt-3">
                                <a href="{{route('wallet.create')}}" class="btn btn-primary">Add wallet</a>
                            </div>
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
                method: 'GET',
                dataType: 'json',
                url: '{{route('api.wallet.index')}}',
                success: function(data) {
                    data.forEach(function(serverWallet) {
                        $('#wallets-table tr').each(function(){
                            if (parseInt($(this).attr('data-id')) === serverWallet.id) {
                                var balanceNode = $(this).children('.balance');
                                if (balanceNode.text() !== serverWallet.balance) {
                                    balanceNode.text(serverWallet.balance).addClass('update-balance');
                                    setInterval(function(){
                                        balanceNode.removeClass('update-balance');
                                    }, 1500);
                                }
                            }
                        });
                    })
                }
            });
        }, 3000);
    </script>
@endpush
